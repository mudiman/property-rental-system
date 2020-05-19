<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserServiceAPIRequest;
use App\Http\Requests\API\UpdateUserServiceAPIRequest;
use App\Models\UserService;
use App\Models\Service;
use App\Repositories\UserServiceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class UserServiceController
 * @package App\Http\Controllers\API
 */

class UserServiceAPIController extends AppBaseController
{
    /** @var  UserServiceRepository */
    private $userServiceRepository;

    public function __construct(UserServiceRepository $userServiceRepo)
    {
        $this->userServiceRepository = $userServiceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/userServices",
     *      summary="Get a listing of the UserServices.",
     *      tags={"UserService"},
     *      description="Get all UserServices",
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
     *                  @SWG\Items(ref="#/definitions/UserService")
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
        $this->userServiceRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->userServiceRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->userServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $userServices = $this->userServiceRepository->all();

        return $this->sendResponse($userServices->toArray(), 'User Services retrieved successfully');
    }

    /**
     * @param CreateUserServiceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/userServices",
     *      summary="Store a newly created UserService in storage",
     *      tags={"UserService"},
     *      description="Store UserService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="UserService that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/UserService")
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
     *                  ref="#/definitions/UserService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserServiceAPIRequest $request)
    {
        $input = $request->all();

        $userServices = $this->userServiceRepository->create($input);

        return $this->sendResponse($userServices->toArray(), 'User Service saved successfully');
    }
    
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/user_services/bulk",
     *      summary="Bulk insert",
     *      tags={"Viewing"},
     *      description="Store user services in bulk",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User services that should be stored",
     *          required=false,
     *          type="array",
     *          @SWG\Schema(ref="#/definitions/UserService")
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
     *                  ref="#/definitions/UserService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function storeBulk(Request $request)
    {
        $input = $request->all();
        $userServices = [];
        foreach ($input as $row) {
          // if no service id then create new service
          if (!isset($row['service_id'])) {
            // crete new service
            if (!isset($row['title'])
                && !isset($row['type'])
                && !isset($row['description'])) {
              return $this->sendError('Please send title, type and description for creating new service', 400);
            }
            $service = Service::create([
              'title' => $row['title'],
              'type' => $row['type'],
              'description' => $row['description'],
            ]);
            $row['service_id'] = $service->id;
          }
          // update user service if id present
          if (isset($row['id'])) {
            UserService::updateOrCreate(
              [
                'id' => $row['id'],
              ],
              $row
            ); 
          } else {
            $userServices[] = $this->userServiceRepository->create($row);
          }
        }

        return $this->sendResponse($userServices, 'bulk saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/userServices/{id}",
     *      summary="Display the specified UserService",
     *      tags={"UserService"},
     *      description="Get UserService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserService",
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
     *                  ref="#/definitions/UserService"
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
        /** @var UserService $userService */
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            return $this->sendError('User Service not found');
        }

        return $this->sendResponse($userService->toArray(), 'User Service retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateUserServiceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/userServices/{id}",
     *      summary="Update the specified UserService in storage",
     *      tags={"UserService"},
     *      description="Update UserService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserService",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="UserService that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/UserService")
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
     *                  ref="#/definitions/UserService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateUserServiceAPIRequest $request)
    {
        $input = $request->all();

        /** @var UserService $userService */
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            return $this->sendError('User Service not found');
        }

        $userService = $this->userServiceRepository->update($input, $id);

        return $this->sendResponse($userService->toArray(), 'UserService updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/userServices/{id}",
     *      summary="Remove the specified UserService from storage",
     *      tags={"UserService"},
     *      description="Delete UserService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserService",
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
    public function destroy($id)
    {
        /** @var UserService $userService */
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            return $this->sendError('User Service not found');
        }

        $userService->delete();

        return $this->sendResponse($id, 'User Service deleted successfully');
    }
}
