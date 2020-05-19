<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceTypeRequest;
use App\Http\Requests\UpdateServiceTypeRequest;
use App\Repositories\ServiceTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ServiceTypeController extends AppBaseController
{
    /** @var  ServiceTypeRepository */
    private $serviceTypeRepository;

    public function __construct(ServiceTypeRepository $serviceTypeRepo)
    {
        $this->serviceTypeRepository = $serviceTypeRepo;
    }

    /**
     * Display a listing of the ServiceType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->serviceTypeRepository->pushCriteria(new RequestCriteria($request));
        $serviceTypes = $this->serviceTypeRepository->all();

        return view('service_types.index')
            ->with('serviceTypes', $serviceTypes);
    }

    /**
     * Show the form for creating a new ServiceType.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_types.create');
    }

    /**
     * Store a newly created ServiceType in storage.
     *
     * @param CreateServiceTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceTypeRequest $request)
    {
        $input = $request->all();

        $serviceType = $this->serviceTypeRepository->create($input);

        Flash::success('Service Type saved successfully.');

        return redirect(route('serviceTypes.index'));
    }

    /**
     * Display the specified ServiceType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            Flash::error('Service Type not found');

            return redirect(route('serviceTypes.index'));
        }

        return view('service_types.show')->with('serviceType', $serviceType);
    }

    /**
     * Show the form for editing the specified ServiceType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            Flash::error('Service Type not found');

            return redirect(route('serviceTypes.index'));
        }

        return view('service_types.edit')->with('serviceType', $serviceType);
    }

    /**
     * Update the specified ServiceType in storage.
     *
     * @param  int              $id
     * @param UpdateServiceTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceTypeRequest $request)
    {
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            Flash::error('Service Type not found');

            return redirect(route('serviceTypes.index'));
        }

        $serviceType = $this->serviceTypeRepository->update($request->all(), $id);

        Flash::success('Service Type updated successfully.');

        return redirect(route('serviceTypes.index'));
    }

    /**
     * Remove the specified ServiceType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            Flash::error('Service Type not found');

            return redirect(route('serviceTypes.index'));
        }

        $this->serviceTypeRepository->delete($id);

        Flash::success('Service Type deleted successfully.');

        return redirect(route('serviceTypes.index'));
    }
}
