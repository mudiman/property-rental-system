<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePropertyServiceRequest;
use App\Http\Requests\UpdatePropertyServiceRequest;
use App\Repositories\PropertyServiceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PropertyServiceController extends AppBaseController
{
    /** @var  PropertyServiceRepository */
    private $propertyServiceRepository;

    public function __construct(PropertyServiceRepository $propertyServiceRepo)
    {
        $this->propertyServiceRepository = $propertyServiceRepo;
    }

    /**
     * Display a listing of the PropertyService.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->propertyServiceRepository->pushCriteria(new RequestCriteria($request));
        $propertyServices = $this->propertyServiceRepository->all();

        return view('property_services.index')
            ->with('propertyServices', $propertyServices);
    }

    /**
     * Show the form for creating a new PropertyService.
     *
     * @return Response
     */
    public function create()
    {
        return view('property_services.create');
    }

    /**
     * Store a newly created PropertyService in storage.
     *
     * @param CreatePropertyServiceRequest $request
     *
     * @return Response
     */
    public function store(CreatePropertyServiceRequest $request)
    {
        $input = $request->all();

        $propertyService = $this->propertyServiceRepository->create($input);

        Flash::success('Property Service saved successfully.');

        return redirect(route('propertyServices.index'));
    }

    /**
     * Display the specified PropertyService.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            Flash::error('Property Service not found');

            return redirect(route('propertyServices.index'));
        }

        return view('property_services.show')->with('propertyService', $propertyService);
    }

    /**
     * Show the form for editing the specified PropertyService.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            Flash::error('Property Service not found');

            return redirect(route('propertyServices.index'));
        }

        return view('property_services.edit')->with('propertyService', $propertyService);
    }

    /**
     * Update the specified PropertyService in storage.
     *
     * @param  int              $id
     * @param UpdatePropertyServiceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePropertyServiceRequest $request)
    {
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            Flash::error('Property Service not found');

            return redirect(route('propertyServices.index'));
        }

        $propertyService = $this->propertyServiceRepository->update($request->all(), $id);

        Flash::success('Property Service updated successfully.');

        return redirect(route('propertyServices.index'));
    }

    /**
     * Remove the specified PropertyService from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            Flash::error('Property Service not found');

            return redirect(route('propertyServices.index'));
        }

        $this->propertyServiceRepository->delete($id);

        Flash::success('Property Service deleted successfully.');

        return redirect(route('propertyServices.index'));
    }
}
