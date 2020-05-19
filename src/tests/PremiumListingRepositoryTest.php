<?php

use App\Models\PremiumListing;
use App\Repositories\PremiumListingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PremiumListingRepositoryTest extends TestCase
{
    use MakePremiumListingTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PremiumListingRepository
     */
    protected $premiumListingRepo;

    public function setUp()
    {
        parent::setUp();
        $this->premiumListingRepo = App::make(PremiumListingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePremiumListing()
    {
        $premiumListing = $this->fakePremiumListingData();
        $createdPremiumListing = $this->premiumListingRepo->create($premiumListing);
        $createdPremiumListing = $createdPremiumListing->toArray();
        $this->assertArrayHasKey('id', $createdPremiumListing);
        $this->assertNotNull($createdPremiumListing['id'], 'Created PremiumListing must have id specified');
        $this->assertNotNull(PremiumListing::find($createdPremiumListing['id']), 'PremiumListing with given id must be in DB');
        $this->assertModelData($premiumListing, $createdPremiumListing);
    }

    /**
     * @test read
     */
    public function testReadPremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $dbPremiumListing = $this->premiumListingRepo->find($premiumListing->id);
        $dbPremiumListing = $dbPremiumListing->toArray();
        $this->assertModelData($premiumListing->toArray(), $dbPremiumListing);
    }

    /**
     * @test update
     */
    public function testUpdatePremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $fakePremiumListing = $this->fakePremiumListingData();
        $updatedPremiumListing = $this->premiumListingRepo->update($fakePremiumListing, $premiumListing->id);
        $this->assertModelData($fakePremiumListing, $updatedPremiumListing->toArray());
        $dbPremiumListing = $this->premiumListingRepo->find($premiumListing->id);
        $this->assertModelData($fakePremiumListing, $dbPremiumListing->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePremiumListing()
    {
        $premiumListing = $this->makePremiumListing();
        $resp = $this->premiumListingRepo->delete($premiumListing->id);
        $this->assertTrue($resp);
        $this->assertNull(PremiumListing::find($premiumListing->id), 'PremiumListing should not exist in DB');
    }
}
