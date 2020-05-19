<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLettingTypeRequest;
use App\Http\Requests\UpdateLettingTypeRequest;
use App\Repositories\LettingTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LettingTypeController extends AppBaseController
{
    /** @var  LettingTypeRepository */
    private $lettingTypeRepository;

    public function __construct(LettingTypeRepository $lettingTypeRepo)
    {
        $this->lettingTypeRepository = $lettingTypeRepo;
    }

    /**
     * Display a listing of the LettingType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->lettingTypeRepository->pushCriteria(new RequestCriteria($request));
        $lettingTypes = $this->lettingTypeRepository->all();

        return view('letting_types.index')
            ->with('lettingTypes', $lettingTypes);
    }

    /**
     * Show the form for creating a new LettingType.
     *
     * @return Response
     */
    public function create()
    {
        return view('letting_types.create');
    }

    /**
     * Store a newly created LettingType in storage.
     *
     * @param CreateLettingTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateLettingTypeRequest $request)
    {
        $input = $request->all();

        $lettingType = $this->lettingTypeRepository->create($input);

        Flash::success('Letting Type saved successfully.');

        return redirect(route('lettingTypes.index'));
    }

    /**
     * Display the specified LettingType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $lettingType = $this->lettingTypeRepository->findWithoutFail($id);

        if (empty($lettingType)) {
            Flash::error('Letting Type not found');

            return redirect(route('lettingTypes.index'));
        }

        return view('letting_types.show')->with('lettingType', $lettingType);
    }

    /**
     * Show the form for editing the specified LettingType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $lettingType = $this->lettingTypeRepository->findWithoutFail($id);

        if (empty($lettingType)) {
            Flash::error('Letting Type not found');

            return redirect(route('lettingTypes.index'));
        }

        return view('letting_types.edit')->with('lettingType', $lettingType);
    }

    /**
     * Update the specified LettingType in storage.
     *
     * @param  int              $id
     * @param UpdateLettingTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLettingTypeRequest $request)
    {
        $lettingType = $this->lettingTypeRepository->findWithoutFail($id);

        if (empty($lettingType)) {
            Flash::error('Letting Type not found');

            return redirect(route('lettingTypes.index'));
        }

        $lettingType = $this->lettingTypeRepository->update($request->all(), $id);

        Flash::success('Letting Type updated successfully.');

        return redirect(route('lettingTypes.index'));
    }

    /**
     * Remove the specified LettingType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $lettingType = $this->lettingTypeRepository->findWithoutFail($id);

        if (empty($lettingType)) {
            Flash::error('Letting Type not found');

            return redirect(route('lettingTypes.index'));
        }

        $this->lettingTypeRepository->delete($id);

        Flash::success('Letting Type deleted successfully.');

        return redirect(route('lettingTypes.index'));
    }
}
