<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScoreTypeRequest;
use App\Http\Requests\UpdateScoreTypeRequest;
use App\Repositories\ScoreTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ScoreTypeController extends AppBaseController
{
    /** @var  ScoreTypeRepository */
    private $scoreTypeRepository;

    public function __construct(ScoreTypeRepository $scoreTypeRepo)
    {
        $this->scoreTypeRepository = $scoreTypeRepo;
    }

    /**
     * Display a listing of the ScoreType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->scoreTypeRepository->pushCriteria(new RequestCriteria($request));
        $scoreTypes = $this->scoreTypeRepository->all();

        return view('score_types.index')
            ->with('scoreTypes', $scoreTypes);
    }

    /**
     * Show the form for creating a new ScoreType.
     *
     * @return Response
     */
    public function create()
    {
        return view('score_types.create');
    }

    /**
     * Store a newly created ScoreType in storage.
     *
     * @param CreateScoreTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateScoreTypeRequest $request)
    {
        $input = $request->all();

        $scoreType = $this->scoreTypeRepository->create($input);

        Flash::success('Score Type saved successfully.');

        return redirect(route('scoreTypes.index'));
    }

    /**
     * Display the specified ScoreType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            Flash::error('Score Type not found');

            return redirect(route('scoreTypes.index'));
        }

        return view('score_types.show')->with('scoreType', $scoreType);
    }

    /**
     * Show the form for editing the specified ScoreType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            Flash::error('Score Type not found');

            return redirect(route('scoreTypes.index'));
        }

        return view('score_types.edit')->with('scoreType', $scoreType);
    }

    /**
     * Update the specified ScoreType in storage.
     *
     * @param  int              $id
     * @param UpdateScoreTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateScoreTypeRequest $request)
    {
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            Flash::error('Score Type not found');

            return redirect(route('scoreTypes.index'));
        }

        $scoreType = $this->scoreTypeRepository->update($request->all(), $id);

        Flash::success('Score Type updated successfully.');

        return redirect(route('scoreTypes.index'));
    }

    /**
     * Remove the specified ScoreType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            Flash::error('Score Type not found');

            return redirect(route('scoreTypes.index'));
        }

        $this->scoreTypeRepository->delete($id);

        Flash::success('Score Type deleted successfully.');

        return redirect(route('scoreTypes.index'));
    }
}
