<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PremiumListingApiTest extends TestCase
{
    use MakePremiumListingTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePremiumListing()
    {
        $premiumListing = $this->fakePremiumListingData();
        $this->json('POST', '/api/v1/premiumListings', $premiumListing);

        $this->assertApiResponse($premiumListing);
    }

    /**
     * @test
     */
    public function testReadPremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $this->json('GET', '/api/v1/premiumListings/'.$premiumListing->id);

        $this->assertApiResponse($premiumListing->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $editedPremiumListing = $this->fakePremiumListingData();

        $this->json('PUT', '/api/v1/premiumListings/'.$premiumListing->id, $editedPremiumListing);

        $this->assertApiResponse($editedPremiumListing);
    }

    /**
     * @test
     */
    public function testDeletePremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $this->json('DELETE', '/api/v1/premiumListings/'.$premiumListing->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/premiumListings/'.$premiumListing->id);

        $this->assertResponseStatus(404);
    }
}
