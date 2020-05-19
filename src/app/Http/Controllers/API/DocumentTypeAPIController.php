<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDocumentTypeAPIRequest;
use App\Http\Requests\API\UpdateDocumentTypeAPIRequest;
use App\Models\DocumentType;
use App\Repositories\DocumentTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class DocumentTypeController
 * @package App\Http\Controllers\API
 */

class DocumentTypeAPIController extends AppBaseController
{
    /** @var  DocumentTypeRepository */
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepo)
    {
        $this->documentTypeRepository = $documentTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/documentTypes",
     *      summary="Get a listing of the DocumentTypes.",
     *      tags={"DocumentType"},
     *      description="Get all DocumentTypes",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/DocumentType")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->documentTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->documentTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->documentTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentTypes = $this->documentTypeRepository->all();

        return $this->sendResponse($documentTypes->toArray(), 'Document Types retrieved successfully');
    }
}
