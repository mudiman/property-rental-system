<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OfferApiTest extends TestCase
{
    use MakeOfferTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateOffer()
    {
        $offer = $this->fakeOfferData();
        $this->json('POST', '/api/v1/offers', $offer);

        $this->assertApiResponse($offer);
    }

    /**
     * @test
     */
    public function testReadOffer()
    {
        $offer = $this->makeOffer();
        $this->json('GET', '/api/v1/offers/'.$offer->id);

        $this->assertApiResponse($offer->toArray());
    }

    /**
     * @test
     */
    public function testUpdateOffer()
    {
        $offer = $this->makeOffer();
        $editedOffer = $this->fakeOfferData();

        $this->json('PUT', '/api/v1/offers/'.$offer->id, $editedOffer);

        $this->assertApiResponse($editedOffer);
    }

    /**
     * @test
     */
    public function testDeleteOffer()
    {
        $offer = $this->makeOffer();
        $this->json('DELETE', '/api/v1/offers/'.$offer->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/offers/'.$offer->id);

        $this->assertResponseStatus(404);
    }
}
