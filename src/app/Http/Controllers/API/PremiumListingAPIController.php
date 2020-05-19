<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePremiumListingAPIRequest;
use App\Http\Requests\API\UpdatePremiumListingAPIRequest;
use App\Models\PremiumListing;
use App\Repositories\PremiumListingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class PremiumListingController
 * @package App\Http\Controllers\API
 */

class PremiumListingAPIController extends AppBaseController
{
    /** @var  PremiumListingRepository */
    private $premiumListingRepository;

    public function __construct(PremiumListingRepository $premiumListingRepo)
    {
        $this->premiumListingRepository = $premiumListingRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/premiumListings",
     *      summary="Get a listing of the PremiumListings.",
     *      tags={"PremiumListing"},
     *      description="Get all PremiumListings",
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
     *                  @SWG\Items(ref="#/definitions/PremiumListing")
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
        $this->premiumListingRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->premiumListingRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->premiumListingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $premiumListings = $this->premiumListingRepository->all();

        return $this->sendResponse($premiumListings->toArray(), 'Premium Listings retrieved successfully');
    }

    /**
     * @param CreatePremiumListingAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/premiumListings",
     *      summary="Store a newly created PremiumListing in storage",
     *      tags={"PremiumListing"},
     *      description="Store PremiumListing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PremiumListing that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PremiumListing")
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
     *                  ref="#/definitions/PremiumListing"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePremiumListingAPIRequest $request)
    {
        $input = $request->all();

        $premiumListings = $this->premiumListingRepository->create($input);

        return $this->sendResponse($premiumListings->toArray(), 'Premium Listing saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/premiumListings/{id}",
     *      summary="Display the specified PremiumListing",
     *      tags={"PremiumListing"},
     *      description="Get PremiumListing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PremiumListing",
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
     *                  ref="#/definitions/PremiumListing"
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
        /** @var PremiumListing $premiumListing */
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            return $this->sendError('Premium Listing not found');
        }

        return $this->sendResponse($premiumListing->toArray(), 'Premium Listing retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePremiumListingAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/premiumListings/{id}",
     *      summary="Update the specified PremiumListing in storage",
     *      tags={"PremiumListing"},
     *      description="Update PremiumListing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PremiumListing",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PremiumListing that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PremiumListing")
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
     *                  ref="#/definitions/PremiumListing"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePremiumListingAPIRequest $request)
    {
        $input = $request->all();

        /** @var PremiumListing $premiumListing */
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            return $this->sendError('Premium Listing not found');
        }

        $premiumListing = $this->premiumListingRepository->update($input, $id);

        return $this->sendResponse($premiumListing->toArray(), 'PremiumListing updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/premiumListings/{id}",
     *      summary="Remove the specified PremiumListing from storage",
     *      tags={"PremiumListing"},
     *      description="Delete PremiumListing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PremiumListing",
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
        /** @var PremiumListing $premiumListing */
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            return $this->sendError('Premium Listing not found');
        }

        $premiumListing->delete();

        return $this->sendResponse($id, 'Premium Listing deleted successfully');
    }
}
