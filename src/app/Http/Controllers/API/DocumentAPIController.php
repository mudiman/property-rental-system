<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDocumentAPIRequest;
use App\Http\Requests\API\UpdateDocumentAPIRequest;
use App\Http\Requests\API\ShowDocumentAPIRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Storage;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class DocumentController
 * @package App\Http\Controllers\API
 */

class DocumentAPIController extends AppBaseController
{
    /** @var  DocumentRepository */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepository = $documentRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/documents",
     *      summary="Get a listing of the Documents.",
     *      tags={"Document"},
     *      description="Get all Documents",
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
     *                  @SWG\Items(ref="#/definitions/Document")
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
        $this->documentRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->documentRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->documentRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documents = $this->documentRepository->all();

        return $this->sendResponse($documents->toArray(), 'Documents retrieved successfully');
    }

    /**
     * @param CreateDocumentAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/documents",
     *      summary="Store a newly created Document in storage",
     *      tags={"Document"},
     *      description="Store Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Document that should be stored. Please send base64 encoded data with mimetype in these parameter Document_data, file_front_data, file_back_data",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="issuing_country",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="type",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="property_id",
     *                  type="int"
     *              ),
     *              @SWG\Property(
     *                  property="user_id",
     *                  type="int"
     *              ),
     *              @SWG\Property(
     *                  property="tenancy_id",
     *                  type="int"
     *              ),
     *              @SWG\Property(
     *                  property="name",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="verified",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="document_data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="file_front_data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="file_back_data",
     *                  type="string"
     *              )
     *          )
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateDocumentAPIRequest $request)
    {
        $input = $request->all();
        
        if (isset($input['document_data'])) {
          $uploadedData = $this->uploadS3Document($input['document_data']);
          $input['path'] = $uploadedData['path'];
          $input['filename'] = $uploadedData['filename'];
          $input['mimetype'] = $uploadedData['mimetype'];
        }
        if (isset($input['file_front_data'])) {
          $uploadedData = $this->uploadS3Document($input['file_front_data']);
          $input['file_front_path'] = $uploadedData['path'];
          $input['file_front_filename'] = $uploadedData['filename'];
          $input['file_front_mimetype'] = $uploadedData['mimetype'];
        }
        if (isset($input['file_back_data'])) {
          $uploadedData = $this->uploadS3Document($input['file_back_data']);
          $input['file_back_path'] = $uploadedData['path'];
          $input['file_back_filename'] = $uploadedData['filename'];
          $input['file_back_mimetype'] = $uploadedData['mimetype'];
        }
        $input['bucket_name'] = $uploadedData['bucket_name'];
        $documents = $this->documentRepository->create($input);

        return $this->sendResponse($documents->toArray(), 'Document saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/documents/{id}",
     *      summary="Display the specified Document",
     *      tags={"Document"},
     *      description="Get Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show(ShowDocumentAPIRequest $request, $id)
    {
        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            return $this->sendError('Document not found');
        }

        return $this->sendResponse($document->toArray(), 'Document retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateDocumentAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/documents/{id}",
     *      summary="Update the specified Document in storage",
     *      tags={"Document"},
     *      description="Update Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Document that should be updated. Like create document_data, file_front_data and file_back_data for replace old documents",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Document")
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateDocumentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            return $this->sendError('Document not found');
        }
        
        if (isset($input['document_data'])) {
          Storage::disk('s3_document')->delete($document->filename);
          $uploadedData = $this->uploadS3Document($input['document_data']);
          $input['path'] = $uploadedData['path'];
          $input['filename'] = $uploadedData['filename'];
          $input['mimetype'] = $uploadedData['mimetype'];
        }
        if (isset($input['file_front_data'])) {
          Storage::disk('s3_document')->delete($document->file_front_filename);
          $uploadedData = $this->uploadS3Document($input['file_front_data']);
          $input['file_front_path'] = $uploadedData['path'];
          $input['file_front_filename'] = $uploadedData['filename'];
          $input['file_front_mimetype'] = $uploadedData['mimetype'];
        }
        if (isset($input['file_back_data'])) {
          Storage::disk('s3_document')->delete($document->file_back_filename);
          $uploadedData = $this->uploadS3Document($input['file_back_data']);
          $input['file_back_path'] = $uploadedData['path'];
          $input['file_back_filename'] = $uploadedData['filename'];
          $input['file_back_mimetype'] = $uploadedData['mimetype'];
        }
        $input['bucket_name'] = $uploadedData['bucket_name'];
        $document = $this->documentRepository->update($input, $id);

        return $this->sendResponse($document->toArray(), 'Document updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/documents/{id}",
     *      summary="Remove the specified Document from storage",
     *      tags={"Document"},
     *      description="Delete Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            return $this->sendError('Document not found');
        }
        Storage::disk('s3_document')->delete($document->filename);
        Storage::disk('s3_document')->delete($document->file_front_path);
        Storage::disk('s3_document')->delete($document->file_back_path);
        $document->delete();

        return $this->sendResponse($id, 'Document deleted successfully');
    }
    
    
    private function uploadS3Document($document_data)
    {
      $img_data = base64_decode(explode(',', $document_data)[1]);
      $pos  = strpos($document_data, ';');
      $mimetype = explode(':', substr($document_data, 0, $pos))[1];
      $type = explode('/', $mimetype)[1];
      $uploadFileName = uniqid().".".$type;
      Storage::disk('s3_document')->put($uploadFileName, $img_data);
      return ['path' => Storage::disk('s3_document')->url($uploadFileName), 'filename' => $uploadFileName , 'mimetype' => $mimetype, 'bucket_name' => config('filesystems.disks.s3_document.bucket')];
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/documents/{id}/document",
     *      summary="Display the main  Document",
     *      tags={"Document"},
     *      description="Get Document",
     *      produces={"document mimetype"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function showDocument(ShowDocumentAPIRequest $request, $id)
    {
        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);
        if (empty($document) || !Storage::cloud('s3_document')->exists($document->filename)) {
            return $this->sendError('Document not found');
        }
        
        return response(Storage::cloud('s3_document')->get($document->filename))
            ->withHeaders([
                'Content-Type' => $document->mimetype,
            ]);
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/documents/{id}/document_front",
     *      summary="Display the specified Document front",
     *      tags={"Document"},
     *      description="Get Document",
     *      produces={"document mimetype"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function showDocumentFront(ShowDocumentAPIRequest $request, $id)
    {
        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);
        if (empty($document) || !Storage::cloud('s3_document')->exists($document->file_front_filename)) {
            return $this->sendError('Document not found');
        }
        
        return response(Storage::cloud('s3_document')->get($document->file_front_filename))
            ->withHeaders([
                'Content-Type' => $document->file_front_mimetype,
            ]);
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/documents/{id}/document_back",
     *      summary="Display the specified Document back",
     *      tags={"Document"},
     *      description="Get Document",
     *      produces={"document mimetype"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Document",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  ref="#/definitions/Document"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function showDocumentBack(ShowDocumentAPIRequest $request, $id)
    {
        /** @var Document $document */
        $document = $this->documentRepository->findWithoutFail($id);
        if (empty($document) || !Storage::cloud('s3_document')->exists($document->file_back_filename)) {
            return $this->sendError('Document not found');
        }
        
        return response(Storage::cloud('s3_document')->get($document->file_back_filename))
            ->withHeaders([
                'Content-Type' => $document->file_back_mimetype,
            ]);
    }
}
