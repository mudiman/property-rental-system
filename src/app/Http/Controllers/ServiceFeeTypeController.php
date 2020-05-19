<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceFeeTypeRequest;
use App\Http\Requests\UpdateServiceFeeTypeRequest;
use App\Repositories\ServiceFeeTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ServiceFeeTypeController extends AppBaseController
{
    /** @var  ServiceFeeTypeRepository */
    private $serviceFeeTypeRepository;

    public function __construct(ServiceFeeTypeRepository $serviceFeeTypeRepo)
    {
        $this->serviceFeeTypeRepository = $serviceFeeTypeRepo;
    }

    /**
     * Display a listing of the ServiceFeeType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->serviceFeeTypeRepository->pushCriteria(new RequestCriteria($request));
        $serviceFeeTypes = $this->serviceFeeTypeRepository->all();

        return view('service_fee_types.index')
            ->with('serviceFeeTypes', $serviceFeeTypes);
    }

    /**
     * Show the form for creating a new ServiceFeeType.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_fee_types.create');
    }

    /**
     * Store a newly created ServiceFeeType in storage.
     *
     * @param CreateServiceFeeTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceFeeTypeRequest $request)
    {
        $input = $request->all();

        $serviceFeeType = $this->serviceFeeTypeRepository->create($input);

        Flash::success('Service Fee Type saved successfully.');

        return redirect(route('serviceFeeTypes.index'));
    }

    /**
     * Display the specified ServiceFeeType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceFeeType = $this->serviceFeeTypeRepository->findWithoutFail($id);

        if (empty($serviceFeeType)) {
            Flash::error('Service Fee Type not found');

            return redirect(route('serviceFeeTypes.index'));
        }

        return view('service_fee_types.show')->with('serviceFeeType', $serviceFeeType);
    }

    /**
     * Show the form for editing the specified ServiceFeeType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceFeeType = $this->serviceFeeTypeRepository->findWithoutFail($id);

        if (empty($serviceFeeType)) {
            Flash::error('Service Fee Type not found');

            return redirect(route('serviceFeeTypes.index'));
        }

        return view('service_fee_types.edit')->with('serviceFeeType', $serviceFeeType);
    }

    /**
     * Update the specified ServiceFeeType in storage.
     *
     * @param  int              $id
     * @param UpdateServiceFeeTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceFeeTypeRequest $request)
    {
        $serviceFeeType = $this->serviceFeeTypeRepository->findWithoutFail($id);

        if (empty($serviceFeeType)) {
            Flash::error('Service Fee Type not found');

            return redirect(route('serviceFeeTypes.index'));
        }

        $serviceFeeType = $this->serviceFeeTypeRepository->update($request->all(), $id);

        Flash::success('Service Fee Type updated successfully.');

        return redirect(route('serviceFeeTypes.index'));
    }

    /**
     * Remove the specified ServiceFeeType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceFeeType = $this->serviceFeeTypeRepository->findWithoutFail($id);

        if (empty($serviceFeeType)) {
            Flash::error('Service Fee Type not found');

            return redirect(route('serviceFeeTypes.index'));
        }

        $this->serviceFeeTypeRepository->delete($id);

        Flash::success('Service Fee Type deleted successfully.');

        return redirect(route('serviceFeeTypes.index'));
    }
}
