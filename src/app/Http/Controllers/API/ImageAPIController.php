<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateImageAPIRequest;
use App\Http\Requests\API\UpdateImageAPIRequest;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;
use Carbon\Carbon;

/**
 * Class ImageController
 * @package App\Http\Controllers\API
 */

class ImageAPIController extends AppBaseController
{
    /** @var  ImageRepository */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepo)
    {
        $this->imageRepository = $imageRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/images",
     *      summary="Get a listing of the Images.",
     *      tags={"Image"},
     *      description="Get all Images",
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
     *                  @SWG\Items(ref="#/definitions/Image")
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
        $this->imageRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->imageRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->imageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $images = $this->imageRepository->all();

        return $this->sendResponse($images->toArray(), 'Images retrieved successfully');
    }
    
    /**
     * @param CreateImageAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/images",
     *      summary="Store a newly created Image in storage",
     *      tags={"Image"},
     *      description="Store Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Image that should be stored. base64 image should image mimetype",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="path",
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
     *                  property="user_service_id",
     *                  type="int"
     *              ),
     *              @SWG\Property(
     *                  property="primary",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="position",
     *                  type="int"
     *              ),
     *              @SWG\Property(
     *                  property="image_data",
     *                  type="string"
     *              ),
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
     *                  ref="#/definitions/Image"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateImageAPIRequest $request)
    {
        $input = $request->all();
        if (isset($input['image_data'])) {
          $uploadedData = $this->uploadS3($input['image_data']);
          $input['path'] = $uploadedData['path'];
          $input['filename'] = $uploadedData['filename'];
          $input['mimetype'] = $uploadedData['mimetype'];
        }
        $images = $this->imageRepository->create($input);

        return $this->sendResponse($images->toArray(), 'Image saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/images/{id}",
     *      summary="Display the specified Image",
     *      tags={"Image"},
     *      description="Get Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
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
     *                  ref="#/definitions/Image"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }

        return $this->sendResponse($image->toArray(), 'Image retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateImageAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/images/{id}",
     *      summary="Update the specified Image in storage",
     *      tags={"Image"},
     *      description="Update Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Image that should be updated. Like create image_data can be pass to update image. Not old image will be deleted",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Image")
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
     *                  ref="#/definitions/Image"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateImageAPIRequest $request)
    {
        $input = $request->all();

        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }
        
        if ($input['image_data']) {
          Storage::disk('s3_image')->delete($image->filename);
          $uploadedData = $this->uploadS3($input['image_data']);
          $input['path'] = $uploadedData['path'];
          $input['filename'] = $uploadedData['filename'];
          $input['mimetype'] = $uploadedData['mimetype'];
        }

        $image = $this->imageRepository->update($input, $id);

        return $this->sendResponse($image->toArray(), 'Image updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/images/{id}",
     *      summary="Remove the specified Image from storage",
     *      tags={"Image"},
     *      description="Delete Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
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
        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }
        Storage::disk('s3_image')->delete($image->filename);
        $image->delete();

        return $this->sendResponse($id, 'Image deleted successfully');
    }
    
    private function uploadS3($image_data)
    {
      $img_data = base64_decode(explode(',', $image_data)[1]);
      $pos  = strpos($image_data, ';');
      $mimetype = explode(':', substr($image_data, 0, $pos))[1];
      $type = explode('/', $mimetype)[1];
      $uploadFileName = uniqid().".".$type;
      Storage::disk('s3_image')->put($uploadFileName, $img_data);
      return ['path' => Storage::disk('s3_image')->url($uploadFileName), 'filename' => $uploadFileName, 'mimetype' => $mimetype ];
    }
}
