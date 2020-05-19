<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Repositories\ThreadRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ThreadController extends AppBaseController
{
    /** @var  ThreadRepository */
    private $threadRepository;

    public function __construct(ThreadRepository $threadRepo)
    {
        $this->threadRepository = $threadRepo;
    }

    /**
     * Display a listing of the Thread.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->threadRepository->pushCriteria(new RequestCriteria($request));
        $threads = $this->threadRepository->all();

        return view('threads.index')
            ->with('threads', $threads);
    }

    /**
     * Show the form for creating a new Thread.
     *
     * @return Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created Thread in storage.
     *
     * @param CreateThreadRequest $request
     *
     * @return Response
     */
    public function store(CreateThreadRequest $request)
    {
        $input = $request->all();

        $thread = $this->threadRepository->create($input);

        Flash::success('Thread saved successfully.');

        return redirect(route('threads.index'));
    }

    /**
     * Display the specified Thread.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            Flash::error('Thread not found');

            return redirect(route('threads.index'));
        }

        return view('threads.show')->with('thread', $thread);
    }

    /**
     * Show the form for editing the specified Thread.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            Flash::error('Thread not found');

            return redirect(route('threads.index'));
        }

        return view('threads.edit')->with('thread', $thread);
    }

    /**
     * Update the specified Thread in storage.
     *
     * @param  int              $id
     * @param UpdateThreadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateThreadRequest $request)
    {
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            Flash::error('Thread not found');

            return redirect(route('threads.index'));
        }

        $thread = $this->threadRepository->update($request->all(), $id);

        Flash::success('Thread updated successfully.');

        return redirect(route('threads.index'));
    }

    /**
     * Remove the specified Thread from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            Flash::error('Thread not found');

            return redirect(route('threads.index'));
        }

        $this->threadRepository->delete($id);

        Flash::success('Thread deleted successfully.');

        return redirect(route('threads.index'));
    }
}
