<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Requests\API\VerifyUserAPIRequest;
use App\Http\Requests\API\DeleteUserAPIRequest;
use App\Http\Requests\API\LogOutUserAPIRequest;
use App\Http\Requests\API\ResendVerificationLinkAPIRequest;
use App\Http\Requests\API\SendForgotPasswordLinkAPIRequest;
use App\Http\Requests\API\UpdateForgotPasswordAPIRequest;
use App\Http\Requests\API\ForgotPasswordLinkAPIRequest;
use App\Http\Requests\API\SendSmsVerificationCodeAPIRequest;
use App\Http\Requests\API\VerifySmsPasswordCodeAPIRequest;
use App\Http\Requests\API\CheckUserEmailExistsAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\DeviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use App\Criteria\LocationRequestCriteria;
use App\Criteria\UserDefaultResponseCriteria;
use App\Presenters\UserPresenter;
use App\Presenters\UserIndexPresenter;
use Response;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users",
     *      summary="Get a listing of the Users.",
     *      tags={"User"},
     *      description="Get all Users also search and sort by specific parameters
     * ?geosearch=cordinate:51,0.1:0.4:<  .This returns users with 0.4 meter from specified lat,log with respect to cordinate field of user
     * ?search=gotham  .This searches on all searchable fields
     * ?search=gotham&searchFields=title:like This search on title field only using like
     * ?search=gotham&searchFields=city:= This search on title field only using = there are also >, <, >=, <= 
     * ?search=name:John Doe;email:john@gmail.com  Provide fieldName:Value in search param for default = to search
     * ?search=name:John;email:john@gmail.com&searchFields=name:like;email:= rovide fieldName:Value in search param and in searchFields provide the operator with field name
     * &filter=id;name  Limit response to this fields only semi colon separate fields
     * &limit  for limit
     * &orderBy=id&sortedBy=desc  order by Field name and sortedBy with order type
     * &with=relation   Using with model relation data is attached with property. 
     * Relations are documents;devices;images;messages;likes;services;payins;payouts;myProperties;manages;viewings;viewingRequests,references;userServices;myMessages;myReferences;propertyProOffers;landlordOffers;",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->userRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->userRepository->pushCriteria(new LocationRequestCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->userRepository->setPresenter(UserIndexPresenter::class);
        $users = $this->userRepository->all();

        return $this->sendResponse($users['data'], 'Users retrieved successfully');
    }

    /**
     * @param CreateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users",
     *      summary="Store a newly created User in storage",
     *      tags={"User"},
     *      description="Store User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();
        $this->userRepository->setPresenter(UserPresenter::class);
        $users = $this->userRepository->create($input);

        return $this->sendResponse($users['data'], 'User saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/{id}",
     *      summary="Display the specified User",
     *      tags={"User"},
     *      description="Get User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var User $user */
        $this->userRepository->setPresenter(UserPresenter::class);
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user['data'], 'User retrieved successfully');
    }

    /**
     * @param UpdateUserAPIRequest $request
     * @param int $id
     * @return Response
     *
     * @SWG\Put(
     *      path="/users/{id}",
     *      summary="Update the specified User in storage",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdateUserAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        $this->userRepository->setPresenter(UserPresenter::class);
        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user['data'], 'User updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/users/{id}",
     *      summary="Remove the specified User from storage",
     *      tags={"User"},
     *      description="Delete User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy(DeleteUserAPIRequest $request, $id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        foreach($user->tokens as $token) {
          $token->revoke();
          $token->delete(); 
        }
        $user->delete();

        return $this->sendResponse($id, 'User deleted successfully');
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/{id}/logout",
     *      summary="Remove the specified User from storage",
     *      tags={"User"},
     *      description="Logout User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User devices token that will be unregistered",
     *          required=true,
     *          @SWG\Schema(
     *            type="object",
     *            @SWG\Property(
     *              property="device_id",
     *              type="string",
     *              description="device id",
     *            )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function logout(LogOutUserAPIRequest $request, $id)
    {
        $input = $request->all();
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        
        $token = $user->token();
        $deviceRepository = \App::make(DeviceRepository::class);
        $device = $deviceRepository->findByField('token_id', $token->id);
        $device->delete();
        
        $token->revoke();
        $token->delete(); 
        
        return $this->sendResponse($id, 'User deleted successfully');
    }
    
    /**
     * @param int $id
     * @param string $email_verification_code
     * @param VerifyUserAPIRequest $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/verify",
     *      summary="Verification link callback to verify user",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be verified",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="email_verification_code",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function verifyEmailRegistrationCode(VerifyUserAPIRequest $request, $id, $email_verification_code)
    {
        /** @var User $user */
        $user = $this->userRepository->findWhere([
            'email_verification_code' => $email_verification_code,
            'id' => $id,
        ])->first();
        if (empty($user)) {
          return $this->sendHtmlError('User not found or invalid email verification code', 400);
        }
        if ($user->email_verification_code_expiry < Carbon::now()) {
          return $this->sendHtmlError('Verification link expired', 400);
        }
        if ($user->verified == 1) {
          return $this->sendHtmlError('You are already verified', 400);
        }
        $user->verified = 1;
        $user->save();
        
        $this->userRepository->sendWelcomeEmail($user);
        
        return view('users.verify')->with('user', $user);
    }
    
    /**
     * @param int $id
     * @param ResendVerificationLinkAPIRequest $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/resend_verification/id",
     *      summary="Rsend verification email",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function resendVerificationLink(ResendVerificationLinkAPIRequest $request, $id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);
        
        if (empty($user)) {
            return $this->sendError('User not found', 400);
        }
        if ($user->verified == 1) {
            return $this->sendError('User is already verified', 400);
        }
        
        $this->userRepository->sendRegistrationVerificationLink($user);
        
        return $this->sendResponse('sent', 'Resent user verification email');
    }
    
    /**
     * @param int $id
     * @param ResendVerificationLinkAPIRequest $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/send_verification_sms/id",
     *      summary="Send verification sms code",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function sendVerificationSmsCode(SendSmsVerificationCodeAPIRequest $request, $id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);
        
        if (empty($user)) {
            return $this->sendError('User not found or invalid email verification code', 400);
        }
        if (empty($user->mobile)) {
            return $this->sendError('User does not have mobile no stored', 400);
        }
//        if ($user->verified == 1) {
//            return $this->sendError('User is already verified', 400);
//        }
        $this->userRepository->sendRegistrationVerificationSMSCode($user);
        
        return $this->sendResponse('sent', 'Sms sent with user verification code');
    }
    
    /**
     * @param int $id
     * @param SendForgotPasswordLLinkAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/send_forgot_password_link",
     *      summary="Send forgot password link",
     *      tags={"User"},
     *      description="Send forgot password link",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be verified",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function sendForgotPasswordLink(SendForgotPasswordLinkAPIRequest $request)
    {
        $input = $request->only(['email']);

        /** @var User $user */
        $user = $this->userRepository->skipPresenter()->findWhere([
            'email' => $input['email'],
        ])->first();
        
        if (empty($user)) {
            return $this->sendError('User not found', 400);
        }
        $this->userRepository->sendForgotPasswordLink($user);
        
        return $this->sendResponse('sent', 'Emailed password reset link');
    }
    
    /**
     * @param int $id
     * @param ForgotPasswordLinkAPIRequest $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/forgot_password_link/{user}/{code}",
     *      summary="forgot password link to enter new password",
     *      tags={"User"},
     *      description="open forgot password link for entering new password",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="enter new password page on sueccessful"
     *      )
     * )
     */
    public function forgotPasswordLink(ForgotPasswordLinkAPIRequest $request, $id, $code)
    {
        /** @var User $user */
        $user = $this->userRepository->skipPresenter()->findWhere([
            'id' => $id,
            'forgot_password_verification_code' => $code,
        ])->first();
        
        if (empty($user)) {
            return $this->sendHtmlError('User not found or invalid forgot password verification link');
        }
        if ($user->forgot_password_verification_code_expiry < Carbon::now()) {
            return $this->sendHtmlError('Forgot password verification link expired', 400);
        }
        return view('users.new_password')->with('user_id', $id)->with('forgot_password_verification_code', $code);
    }
    
    /**
     * @param int $id
     * @param UpdateForgotPasswordAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/update_password",
     *      summary="Update user password",
     *      tags={"User"},
     *      description="Update forgot password code",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User needs to send forgot password verification code and new password",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function updateForgotPasswordCode(UpdateForgotPasswordAPIRequest $request, $id, $code)
    {
        $input = $request->only(['password']);

        /** @var User $user */
        $user = $this->userRepository->skipPresenter()->findWhere([
            'id' => $id,
            'forgot_password_verification_code' => $code,
        ])->first();
        if (empty($user)) {
            return $this->sendHtmlError('Invalid forgot password verification code or user', '400');
        }
        if ($user->forgot_password_verification_code_expiry < Carbon::now()) {
            return $this->sendHtmlError('Forgot password verification code expired', '400');
        }
        if (!$user->forgot_password_verification_code) {
            return $this->sendHtmlError('Forgot password verification link already used', '400');
        }
        $user->forgot_password_verification_code = null;
        $user->forgot_password_verification_code_expiry = null;
        $user->password = $input['password'];
        $user->save();
        
        foreach($user->tokens as $token) {
          $token->revoke();
          $token->delete(); 
        }
        
        return view('users.new_password_set');
    }
    
    /**
     * @param int $id
     * @param VerifySmsPasswordCodeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/verify_sms_code/id",
     *      summary="Verify sms code",
     *      tags={"User"},
     *      description="Verify sms code",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be verified",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="sms_verification_code",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function verifySMSPasswordCode(VerifySmsPasswordCodeAPIRequest $request, $id)
    {
        $input = $request->only(['sms_verification_code']);

        /** @var User $user */
        $user = $this->userRepository->skipPresenter()->findWhere([
            'id' => $id,
            'sms_verification_code' => $input['sms_verification_code'],
        ])->first();
        if (empty($user)) {
            return $this->sendError('User not found or invalid verification code');
        }
        if ($user->sms_verification_code_expiry < Carbon::now()) {
            return $this->sendError('Verification code expired', 400);
        }
        if ($user->verified == 1) {
          return $this->sendError('You are already verified', 400);
        }
        $user->verified = 1;
        $user->save();
        
        $this->userRepository->setPresenter(UserPresenter::class);
        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse('success', 'User verified');
    }
    
    /**
     * @param CheckUserEmailExistsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/email/exists",
     *      summary="Check user with particular email exists.",
     *      tags={"User"},
     *      description="Get all Users also search and sort by specific parameters",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be verified",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function checkUserExists(CheckUserEmailExistsAPIRequest $request)
    {
        $input = $request->only(['email']);
        
        $user = $this->userRepository->skipPresenter()->findWhere([
            'email' => $input['email'],
        ])->first();
        if (empty($user)) {
            return $this->sendError('User not found or invalid verification code');
        }
        return $this->sendResponse('exists', 'Users retrieved successfully');
    }
    
    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/verify_id/{id}",
     *      summary="validate token with user id",
     *      tags={"User"},
     *      description="validate token with user id",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="validate token with user id"
     *      )
     * )
     */
    public function checkUserIdWithTokenMatch(Request $request, $id)
    {
      if (Auth::user()->id != $id) {
        return $this->sendError('User not same');
      }
      return $this->sendResponse('success', 'User same');
    }
}
