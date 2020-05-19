<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExtraRequest;
use App\Http\Requests\UpdateExtraRequest;
use App\Repositories\ExtraRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ExtraController extends AppBaseController
{
    /** @var  ExtraRepository */
    private $extraRepository;

    public function __construct(ExtraRepository $extraRepo)
    {
        $this->extraRepository = $extraRepo;
    }

    /**
     * Display a listing of the Extra.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->extraRepository->pushCriteria(new RequestCriteria($request));
        $extras = $this->extraRepository->all();

        return view('extras.index')
            ->with('extras', $extras);
    }

    /**
     * Show the form for creating a new Extra.
     *
     * @return Response
     */
    public function create()
    {
        return view('extras.create');
    }

    /**
     * Store a newly created Extra in storage.
     *
     * @param CreateExtraRequest $request
     *
     * @return Response
     */
    public function store(CreateExtraRequest $request)
    {
        $input = $request->all();

        $extra = $this->extraRepository->create($input);

        Flash::success('Extra saved successfully.');

        return redirect(route('extras.index'));
    }

    /**
     * Display the specified Extra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        return view('extras.show')->with('extra', $extra);
    }

    /**
     * Show the form for editing the specified Extra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        return view('extras.edit')->with('extra', $extra);
    }

    /**
     * Update the specified Extra in storage.
     *
     * @param  int              $id
     * @param UpdateExtraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExtraRequest $request)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        $extra = $this->extraRepository->update($request->all(), $id);

        Flash::success('Extra updated successfully.');

        return redirect(route('extras.index'));
    }

    /**
     * Remove the specified Extra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        $this->extraRepository->delete($id);

        Flash::success('Extra deleted successfully.');

        return redirect(route('extras.index'));
    }
}
