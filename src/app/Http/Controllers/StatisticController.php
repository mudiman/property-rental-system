<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStatisticRequest;
use App\Http\Requests\UpdateStatisticRequest;
use App\Repositories\StatisticRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class StatisticController extends AppBaseController
{
    /** @var  StatisticRepository */
    private $statisticRepository;

    public function __construct(StatisticRepository $statisticRepo)
    {
        $this->statisticRepository = $statisticRepo;
    }

    /**
     * Display a listing of the Statistic.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->statisticRepository->pushCriteria(new RequestCriteria($request));
        $statistics = $this->statisticRepository->all();

        return view('statistics.index')
            ->with('statistics', $statistics);
    }

    /**
     * Show the form for creating a new Statistic.
     *
     * @return Response
     */
    public function create()
    {
        return view('statistics.create');
    }

    /**
     * Store a newly created Statistic in storage.
     *
     * @param CreateStatisticRequest $request
     *
     * @return Response
     */
    public function store(CreateStatisticRequest $request)
    {
        $input = $request->all();

        $statistic = $this->statisticRepository->create($input);

        Flash::success('Statistic saved successfully.');

        return redirect(route('statistics.index'));
    }

    /**
     * Display the specified Statistic.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            Flash::error('Statistic not found');

            return redirect(route('statistics.index'));
        }

        return view('statistics.show')->with('statistic', $statistic);
    }

    /**
     * Show the form for editing the specified Statistic.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            Flash::error('Statistic not found');

            return redirect(route('statistics.index'));
        }

        return view('statistics.edit')->with('statistic', $statistic);
    }

    /**
     * Update the specified Statistic in storage.
     *
     * @param  int              $id
     * @param UpdateStatisticRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStatisticRequest $request)
    {
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            Flash::error('Statistic not found');

            return redirect(route('statistics.index'));
        }

        $statistic = $this->statisticRepository->update($request->all(), $id);

        Flash::success('Statistic updated successfully.');

        return redirect(route('statistics.index'));
    }

    /**
     * Remove the specified Statistic from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            Flash::error('Statistic not found');

            return redirect(route('statistics.index'));
        }

        $this->statisticRepository->delete($id);

        Flash::success('Statistic deleted successfully.');

        return redirect(route('statistics.index'));
    }
}
