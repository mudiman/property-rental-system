<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePremiumListingRequest;
use App\Http\Requests\UpdatePremiumListingRequest;
use App\Repositories\PremiumListingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PremiumListingController extends AppBaseController
{
    /** @var  PremiumListingRepository */
    private $premiumListingRepository;

    public function __construct(PremiumListingRepository $premiumListingRepo)
    {
        $this->premiumListingRepository = $premiumListingRepo;
    }

    /**
     * Display a listing of the PremiumListing.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->premiumListingRepository->pushCriteria(new RequestCriteria($request));
        $premiumListings = $this->premiumListingRepository->all();

        return view('premium_listings.index')
            ->with('premiumListings', $premiumListings);
    }

    /**
     * Show the form for creating a new PremiumListing.
     *
     * @return Response
     */
    public function create()
    {
        return view('premium_listings.create');
    }

    /**
     * Store a newly created PremiumListing in storage.
     *
     * @param CreatePremiumListingRequest $request
     *
     * @return Response
     */
    public function store(CreatePremiumListingRequest $request)
    {
        $input = $request->all();

        $premiumListing = $this->premiumListingRepository->create($input);

        Flash::success('Premium Listing saved successfully.');

        return redirect(route('premiumListings.index'));
    }

    /**
     * Display the specified PremiumListing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            Flash::error('Premium Listing not found');

            return redirect(route('premiumListings.index'));
        }

        return view('premium_listings.show')->with('premiumListing', $premiumListing);
    }

    /**
     * Show the form for editing the specified PremiumListing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            Flash::error('Premium Listing not found');

            return redirect(route('premiumListings.index'));
        }

        return view('premium_listings.edit')->with('premiumListing', $premiumListing);
    }

    /**
     * Update the specified PremiumListing in storage.
     *
     * @param  int              $id
     * @param UpdatePremiumListingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePremiumListingRequest $request)
    {
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            Flash::error('Premium Listing not found');

            return redirect(route('premiumListings.index'));
        }

        $premiumListing = $this->premiumListingRepository->update($request->all(), $id);

        Flash::success('Premium Listing updated successfully.');

        return redirect(route('premiumListings.index'));
    }

    /**
     * Remove the specified PremiumListing from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $premiumListing = $this->premiumListingRepository->findWithoutFail($id);

        if (empty($premiumListing)) {
            Flash::error('Premium Listing not found');

            return redirect(route('premiumListings.index'));
        }

        $this->premiumListingRepository->delete($id);

        Flash::success('Premium Listing deleted successfully.');

        return redirect(route('premiumListings.index'));
    }
}
