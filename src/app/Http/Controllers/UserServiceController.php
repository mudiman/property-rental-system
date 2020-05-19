<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserServiceRequest;
use App\Http\Requests\UpdateUserServiceRequest;
use App\Repositories\UserServiceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class UserServiceController extends AppBaseController
{
    /** @var  UserServiceRepository */
    private $userServiceRepository;

    public function __construct(UserServiceRepository $userServiceRepo)
    {
        $this->userServiceRepository = $userServiceRepo;
    }

    /**
     * Display a listing of the UserService.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userServiceRepository->pushCriteria(new RequestCriteria($request));
        $userServices = $this->userServiceRepository->all();

        return view('user_services.index')
            ->with('userServices', $userServices);
    }

    /**
     * Show the form for creating a new UserService.
     *
     * @return Response
     */
    public function create()
    {
        return view('user_services.create');
    }

    /**
     * Store a newly created UserService in storage.
     *
     * @param CreateUserServiceRequest $request
     *
     * @return Response
     */
    public function store(CreateUserServiceRequest $request)
    {
        $input = $request->all();

        $userService = $this->userServiceRepository->create($input);

        Flash::success('User Service saved successfully.');

        return redirect(route('userServices.index'));
    }

    /**
     * Display the specified UserService.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            Flash::error('User Service not found');

            return redirect(route('userServices.index'));
        }

        return view('user_services.show')->with('userService', $userService);
    }

    /**
     * Show the form for editing the specified UserService.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            Flash::error('User Service not found');

            return redirect(route('userServices.index'));
        }

        return view('user_services.edit')->with('userService', $userService);
    }

    /**
     * Update the specified UserService in storage.
     *
     * @param  int              $id
     * @param UpdateUserServiceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserServiceRequest $request)
    {
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            Flash::error('User Service not found');

            return redirect(route('userServices.index'));
        }

        $userService = $this->userServiceRepository->update($request->all(), $id);

        Flash::success('User Service updated successfully.');

        return redirect(route('userServices.index'));
    }

    /**
     * Remove the specified UserService from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $userService = $this->userServiceRepository->findWithoutFail($id);

        if (empty($userService)) {
            Flash::error('User Service not found');

            return redirect(route('userServices.index'));
        }

        $this->userServiceRepository->delete($id);

        Flash::success('User Service deleted successfully.');

        return redirect(route('userServices.index'));
    }
}
