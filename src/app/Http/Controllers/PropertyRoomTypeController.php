<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePropertyRoomTypeRequest;
use App\Http\Requests\UpdatePropertyRoomTypeRequest;
use App\Repositories\PropertyRoomTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PropertyRoomTypeController extends AppBaseController
{
    /** @var  PropertyRoomTypeRepository */
    private $propertyRoomTypeRepository;

    public function __construct(PropertyRoomTypeRepository $propertyRoomTypeRepo)
    {
        $this->propertyRoomTypeRepository = $propertyRoomTypeRepo;
    }

    /**
     * Display a listing of the PropertyRoomType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->propertyRoomTypeRepository->pushCriteria(new RequestCriteria($request));
        $propertyRoomTypes = $this->propertyRoomTypeRepository->all();

        return view('property_room_types.index')
            ->with('propertyRoomTypes', $propertyRoomTypes);
    }

    /**
     * Show the form for creating a new PropertyRoomType.
     *
     * @return Response
     */
    public function create()
    {
        return view('property_room_types.create');
    }

    /**
     * Store a newly created PropertyRoomType in storage.
     *
     * @param CreatePropertyRoomTypeRequest $request
     *
     * @return Response
     */
    public function store(CreatePropertyRoomTypeRequest $request)
    {
        $input = $request->all();

        $propertyRoomType = $this->propertyRoomTypeRepository->create($input);

        Flash::success('Property Room Type saved successfully.');

        return redirect(route('propertyRoomTypes.index'));
    }

    /**
     * Display the specified PropertyRoomType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $propertyRoomType = $this->propertyRoomTypeRepository->findWithoutFail($id);

        if (empty($propertyRoomType)) {
            Flash::error('Property Room Type not found');

            return redirect(route('propertyRoomTypes.index'));
        }

        return view('property_room_types.show')->with('propertyRoomType', $propertyRoomType);
    }

    /**
     * Show the form for editing the specified PropertyRoomType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $propertyRoomType = $this->propertyRoomTypeRepository->findWithoutFail($id);

        if (empty($propertyRoomType)) {
            Flash::error('Property Room Type not found');

            return redirect(route('propertyRoomTypes.index'));
        }

        return view('property_room_types.edit')->with('propertyRoomType', $propertyRoomType);
    }

    /**
     * Update the specified PropertyRoomType in storage.
     *
     * @param  int              $id
     * @param UpdatePropertyRoomTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePropertyRoomTypeRequest $request)
    {
        $propertyRoomType = $this->propertyRoomTypeRepository->findWithoutFail($id);

        if (empty($propertyRoomType)) {
            Flash::error('Property Room Type not found');

            return redirect(route('propertyRoomTypes.index'));
        }

        $propertyRoomType = $this->propertyRoomTypeRepository->update($request->all(), $id);

        Flash::success('Property Room Type updated successfully.');

        return redirect(route('propertyRoomTypes.index'));
    }

    /**
     * Remove the specified PropertyRoomType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $propertyRoomType = $this->propertyRoomTypeRepository->findWithoutFail($id);

        if (empty($propertyRoomType)) {
            Flash::error('Property Room Type not found');

            return redirect(route('propertyRoomTypes.index'));
        }

        $this->propertyRoomTypeRepository->delete($id);

        Flash::success('Property Room Type deleted successfully.');

        return redirect(route('propertyRoomTypes.index'));
    }
}
