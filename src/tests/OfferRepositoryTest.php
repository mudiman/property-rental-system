<?php

use App\Models\Offer;
use App\Repositories\OfferRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OfferRepositoryTest extends TestCase
{
    use MakeOfferTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var OfferRepository
     */
    protected $offerRepo;

    public function setUp()
    {
        parent::setUp();
        $this->offerRepo = App::make(OfferRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateOffer()
    {
        $offer = $this->fakeOfferData();
        $createdOffer = $this->offerRepo->create($offer);
        $createdOffer = $createdOffer->toArray();
        $this->assertArrayHasKey('id', $createdOffer);
        $this->assertNotNull($createdOffer['id'], 'Created Offer must have id specified');
        $this->assertNotNull(Offer::find($createdOffer['id']), 'Offer with given id must be in DB');
        $this->assertModelData($offer, $createdOffer);
    }

    /**
     * @test read
     */
    public function testReadOffer()
    {
        $offer = $this->makeOffer();
        $dbOffer = $this->offerRepo->find($offer->id);
        $dbOffer = $dbOffer->toArray();
        $this->assertModelData($offer->toArray(), $dbOffer);
    }

    /**
     * @test update
     */
    public function testUpdateOffer()
    {
        $offer = $this->makeOffer();
        $fakeOffer = $this->fakeOfferData();
        $updatedOffer = $this->offerRepo->update($fakeOffer, $offer->id);
        $this->assertModelData($fakeOffer, $updatedOffer->toArray());
        $dbOffer = $this->offerRepo->find($offer->id);
        $this->assertModelData($fakeOffer, $dbOffer->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteOffer()
    {
        $offer = $this->makeOffer();
        $resp = $this->offerRepo->delete($offer->id);
        $this->assertTrue($resp);
        $this->assertNull(Offer::find($offer->id), 'Offer should not exist in DB');
    }
}
