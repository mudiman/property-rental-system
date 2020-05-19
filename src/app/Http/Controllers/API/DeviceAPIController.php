<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDeviceAPIRequest;
use App\Http\Requests\API\UpdateDeviceAPIRequest;
use App\Models\Device;
use App\Repositories\DeviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeviceController
 * @package App\Http\Controllers\API
 */

class DeviceAPIController extends AppBaseController
{
    /** @var  DeviceRepository */
    private $deviceRepository;

    public function __construct(DeviceRepository $deviceRepo)
    {
        $this->deviceRepository = $deviceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/devices",
     *      summary="Get a listing of the Devices.",
     *      tags={"Device"},
     *      description="Get all Devices",
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
     *                  @SWG\Items(ref="#/definitions/Device")
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
        $this->deviceRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->deviceRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->deviceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $devices = $this->deviceRepository->all();

        return $this->sendResponse($devices->toArray(), 'Devices retrieved successfully');
    }

    /**
     * @param CreateDeviceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/devices",
     *      summary="Store a newly created Device in storage",
     *      tags={"Device"},
     *      description="Store Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Device that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Device")
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
     *                  ref="#/definitions/Device"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateDeviceAPIRequest $request)
    {
        $input = $request->all();
        $input['token_id'] = Auth::user()->token()->id;
        $devices = $this->deviceRepository->create($input);

        return $this->sendResponse($devices->toArray(), 'Device saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/devices/{id}",
     *      summary="Display the specified Device",
     *      tags={"Device"},
     *      description="Get Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
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
     *                  ref="#/definitions/Device"
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
        /** @var Device $device */
        $device = $this->deviceRepository->findWithoutFail($id);

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        return $this->sendResponse($device->toArray(), 'Device retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateDeviceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/devices/{id}",
     *      summary="Update the specified Device in storage",
     *      tags={"Device"},
     *      description="Update Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Device that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Device")
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
     *                  ref="#/definitions/Device"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateDeviceAPIRequest $request)
    {
        $input = $request->all();
        $input['token_id'] = Auth::user()->token()->id;
        /** @var Device $device */
        $device = $this->deviceRepository->findWithoutFail($id);

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        $device = $this->deviceRepository->update($input, $id);

        return $this->sendResponse($device->toArray(), 'Device updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/devices/{id}",
     *      summary="Remove the specified Device from storage",
     *      tags={"Device"},
     *      description="Delete Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
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
        /** @var Device $device */
        $device = $this->deviceRepository->findWithoutFail($id);

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        $device->delete();

        return $this->sendResponse($id, 'Device deleted successfully');
    }
}
