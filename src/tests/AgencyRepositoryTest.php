<?php

use App\Models\Agency;
use App\Repositories\AgencyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AgencyRepositoryTest extends TestCase
{
    use MakeAgencyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AgencyRepository
     */
    protected $agencyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->agencyRepo = App::make(AgencyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAgency()
    {
        $agency = $this->fakeAgencyData();
        $createdAgency = $this->agencyRepo->create($agency);
        $createdAgency = $createdAgency->toArray();
        $this->assertArrayHasKey('id', $createdAgency);
        $this->assertNotNull($createdAgency['id'], 'Created Agency must have id specified');
        $this->assertNotNull(Agency::find($createdAgency['id']), 'Agency with given id must be in DB');
        $this->assertModelData($agency, $createdAgency);
    }

    /**
     * @test read
     */
    public function testReadAgency()
    {
        $agency = $this->makeAgency();
        $dbAgency = $this->agencyRepo->find($agency->id);
        $dbAgency = $dbAgency->toArray();
        $this->assertModelData($agency->toArray(), $dbAgency);
    }

    /**
     * @test update
     */
    public function testUpdateAgency()
    {
        $agency = $this->makeAgency();
        $fakeAgency = $this->fakeAgencyData();
        $updatedAgency = $this->agencyRepo->update($fakeAgency, $agency->id);
        $this->assertModelData($fakeAgency, $updatedAgency->toArray());
        $dbAgency = $this->agencyRepo->find($agency->id);
        $this->assertModelData($fakeAgency, $dbAgency->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAgency()
    {
        $agency = $this->makeAgency();
        $resp = $this->agencyRepo->delete($agency->id);
        $this->assertTrue($resp);
        $this->assertNull(Agency::find($agency->id), 'Agency should not exist in DB');
    }
}
