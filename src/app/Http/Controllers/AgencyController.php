<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Repositories\AgencyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AgencyController extends AppBaseController
{
    /** @var  AgencyRepository */
    private $agencyRepository;

    public function __construct(AgencyRepository $agencyRepo)
    {
        $this->agencyRepository = $agencyRepo;
    }

    /**
     * Display a listing of the Agency.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->agencyRepository->pushCriteria(new RequestCriteria($request));
        $agencies = $this->agencyRepository->all();

        return view('agencies.index')
            ->with('agencies', $agencies);
    }

    /**
     * Show the form for creating a new Agency.
     *
     * @return Response
     */
    public function create()
    {
        return view('agencies.create');
    }

    /**
     * Store a newly created Agency in storage.
     *
     * @param CreateAgencyRequest $request
     *
     * @return Response
     */
    public function store(CreateAgencyRequest $request)
    {
        $input = $request->all();

        $agency = $this->agencyRepository->create($input);

        Flash::success('Agency saved successfully.');

        return redirect(route('agencies.index'));
    }

    /**
     * Display the specified Agency.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            Flash::error('Agency not found');

            return redirect(route('agencies.index'));
        }

        return view('agencies.show')->with('agency', $agency);
    }

    /**
     * Show the form for editing the specified Agency.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            Flash::error('Agency not found');

            return redirect(route('agencies.index'));
        }

        return view('agencies.edit')->with('agency', $agency);
    }

    /**
     * Update the specified Agency in storage.
     *
     * @param  int              $id
     * @param UpdateAgencyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAgencyRequest $request)
    {
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            Flash::error('Agency not found');

            return redirect(route('agencies.index'));
        }

        $agency = $this->agencyRepository->update($request->all(), $id);

        Flash::success('Agency updated successfully.');

        return redirect(route('agencies.index'));
    }

    /**
     * Remove the specified Agency from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            Flash::error('Agency not found');

            return redirect(route('agencies.index'));
        }

        $this->agencyRepository->delete($id);

        Flash::success('Agency deleted successfully.');

        return redirect(route('agencies.index'));
    }
}
