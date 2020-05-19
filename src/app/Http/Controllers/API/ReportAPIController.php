<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateReportAPIRequest;
use App\Http\Requests\API\UpdateReportAPIRequest;
use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ReportController
 * @package App\Http\Controllers\API
 */

class ReportAPIController extends AppBaseController
{
    /** @var  ReportRepository */
    private $reportRepository;

    public function __construct(ReportRepository $reportRepo)
    {
        $this->reportRepository = $reportRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/reports",
     *      summary="Get a listing of the Reports.",
     *      tags={"Report"},
     *      description="Get all Reports",
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
     *                  @SWG\Items(ref="#/definitions/Report")
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
        $this->reportRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->reportRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->reportRepository->pushCriteria(new LimitOffsetCriteria($request));
        $reports = $this->reportRepository->all();

        return $this->sendResponse($reports->toArray(), 'Reports retrieved successfully');
    }

    /**
     * @param CreateReportAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/reports",
     *      summary="Store a newly created Report in storage",
     *      tags={"Report"},
     *      description="Store Report",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Report that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Report")
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
     *                  ref="#/definitions/Report"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateReportAPIRequest $request)
    {
        $input = $request->all();

        $reports = $this->reportRepository->create($input);

        return $this->sendResponse($reports->toArray(), 'Report saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/reports/{id}",
     *      summary="Display the specified Report",
     *      tags={"Report"},
     *      description="Get Report",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Report",
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
     *                  ref="#/definitions/Report"
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
        /** @var Report $report */
        $report = $this->reportRepository->findWithoutFail($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        return $this->sendResponse($report->toArray(), 'Report retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateReportAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/reports/{id}",
     *      summary="Update the specified Report in storage",
     *      tags={"Report"},
     *      description="Update Report",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Report",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Report that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Report")
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
     *                  ref="#/definitions/Report"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateReportAPIRequest $request)
    {
        $input = $request->all();

        /** @var Report $report */
        $report = $this->reportRepository->findWithoutFail($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        $report = $this->reportRepository->update($input, $id);

        return $this->sendResponse($report->toArray(), 'Report updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/reports/{id}",
     *      summary="Remove the specified Report from storage",
     *      tags={"Report"},
     *      description="Delete Report",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Report",
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
        /** @var Report $report */
        $report = $this->reportRepository->findWithoutFail($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        $report->delete();

        return $this->sendResponse($id, 'Report deleted successfully');
    }
}
