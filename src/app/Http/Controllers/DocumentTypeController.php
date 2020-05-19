<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Repositories\DocumentTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class DocumentTypeController extends AppBaseController
{
    /** @var  DocumentTypeRepository */
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepo)
    {
        $this->documentTypeRepository = $documentTypeRepo;
    }

    /**
     * Display a listing of the DocumentType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentTypeRepository->pushCriteria(new RequestCriteria($request));
        $documentTypes = $this->documentTypeRepository->all();

        return view('document_types.index')
            ->with('documentTypes', $documentTypes);
    }

    /**
     * Show the form for creating a new DocumentType.
     *
     * @return Response
     */
    public function create()
    {
        return view('document_types.create');
    }

    /**
     * Store a newly created DocumentType in storage.
     *
     * @param CreateDocumentTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentTypeRequest $request)
    {
        $input = $request->all();

        $documentType = $this->documentTypeRepository->create($input);

        Flash::success('Document Type saved successfully.');

        return redirect(route('documentTypes.index'));
    }

    /**
     * Display the specified DocumentType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $documentType = $this->documentTypeRepository->findWithoutFail($id);

        if (empty($documentType)) {
            Flash::error('Document Type not found');

            return redirect(route('documentTypes.index'));
        }

        return view('document_types.show')->with('documentType', $documentType);
    }

    /**
     * Show the form for editing the specified DocumentType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $documentType = $this->documentTypeRepository->findWithoutFail($id);

        if (empty($documentType)) {
            Flash::error('Document Type not found');

            return redirect(route('documentTypes.index'));
        }

        return view('document_types.edit')->with('documentType', $documentType);
    }

    /**
     * Update the specified DocumentType in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentTypeRequest $request)
    {
        $documentType = $this->documentTypeRepository->findWithoutFail($id);

        if (empty($documentType)) {
            Flash::error('Document Type not found');

            return redirect(route('documentTypes.index'));
        }

        $documentType = $this->documentTypeRepository->update($request->all(), $id);

        Flash::success('Document Type updated successfully.');

        return redirect(route('documentTypes.index'));
    }

    /**
     * Remove the specified DocumentType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $documentType = $this->documentTypeRepository->findWithoutFail($id);

        if (empty($documentType)) {
            Flash::error('Document Type not found');

            return redirect(route('documentTypes.index'));
        }

        $this->documentTypeRepository->delete($id);

        Flash::success('Document Type deleted successfully.');

        return redirect(route('documentTypes.index'));
    }
}
