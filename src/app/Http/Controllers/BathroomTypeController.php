<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBathroomTypeRequest;
use App\Http\Requests\UpdateBathroomTypeRequest;
use App\Repositories\BathroomTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class BathroomTypeController extends AppBaseController
{
    /** @var  BathroomTypeRepository */
    private $bathroomTypeRepository;

    public function __construct(BathroomTypeRepository $bathroomTypeRepo)
    {
        $this->bathroomTypeRepository = $bathroomTypeRepo;
    }

    /**
     * Display a listing of the BathroomType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->bathroomTypeRepository->pushCriteria(new RequestCriteria($request));
        $bathroomTypes = $this->bathroomTypeRepository->all();

        return view('bathroom_types.index')
            ->with('bathroomTypes', $bathroomTypes);
    }

    /**
     * Show the form for creating a new BathroomType.
     *
     * @return Response
     */
    public function create()
    {
        return view('bathroom_types.create');
    }

    /**
     * Store a newly created BathroomType in storage.
     *
     * @param CreateBathroomTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateBathroomTypeRequest $request)
    {
        $input = $request->all();

        $bathroomType = $this->bathroomTypeRepository->create($input);

        Flash::success('Bathroom Type saved successfully.');

        return redirect(route('bathroomTypes.index'));
    }

    /**
     * Display the specified BathroomType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bathroomType = $this->bathroomTypeRepository->findWithoutFail($id);

        if (empty($bathroomType)) {
            Flash::error('Bathroom Type not found');

            return redirect(route('bathroomTypes.index'));
        }

        return view('bathroom_types.show')->with('bathroomType', $bathroomType);
    }

    /**
     * Show the form for editing the specified BathroomType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bathroomType = $this->bathroomTypeRepository->findWithoutFail($id);

        if (empty($bathroomType)) {
            Flash::error('Bathroom Type not found');

            return redirect(route('bathroomTypes.index'));
        }

        return view('bathroom_types.edit')->with('bathroomType', $bathroomType);
    }

    /**
     * Update the specified BathroomType in storage.
     *
     * @param  int              $id
     * @param UpdateBathroomTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBathroomTypeRequest $request)
    {
        $bathroomType = $this->bathroomTypeRepository->findWithoutFail($id);

        if (empty($bathroomType)) {
            Flash::error('Bathroom Type not found');

            return redirect(route('bathroomTypes.index'));
        }

        $bathroomType = $this->bathroomTypeRepository->update($request->all(), $id);

        Flash::success('Bathroom Type updated successfully.');

        return redirect(route('bathroomTypes.index'));
    }

    /**
     * Remove the specified BathroomType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bathroomType = $this->bathroomTypeRepository->findWithoutFail($id);

        if (empty($bathroomType)) {
            Flash::error('Bathroom Type not found');

            return redirect(route('bathroomTypes.index'));
        }

        $this->bathroomTypeRepository->delete($id);

        Flash::success('Bathroom Type deleted successfully.');

        return redirect(route('bathroomTypes.index'));
    }
}
