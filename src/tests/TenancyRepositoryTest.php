<?php

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TenancyRepositoryTest extends TestCase
{
    use MakeTenancyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var TenancyRepository
     */
    protected $tenancyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->tenancyRepo = App::make(TenancyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateTenancy()
    {
        $tenancy = $this->fakeTenancyData();
        $createdTenancy = $this->tenancyRepo->create($tenancy);
        $createdTenancy = $createdTenancy->toArray();
        $this->assertArrayHasKey('id', $createdTenancy);
        $this->assertNotNull($createdTenancy['id'], 'Created Tenancy must have id specified');
        $this->assertNotNull(Tenancy::find($createdTenancy['id']), 'Tenancy with given id must be in DB');
        $this->assertModelData($tenancy, $createdTenancy);
    }

    /**
     * @test read
     */
    public function testReadTenancy()
    {
        $tenancy = $this->makeTenancy();
        $dbTenancy = $this->tenancyRepo->find($tenancy->id);
        $dbTenancy = $dbTenancy->toArray();
        $this->assertModelData($tenancy->toArray(), $dbTenancy);
    }

    /**
     * @test update
     */
    public function testUpdateTenancy()
    {
        $tenancy = $this->makeTenancy();
        $fakeTenancy = $this->fakeTenancyData();
        $updatedTenancy = $this->tenancyRepo->update($fakeTenancy, $tenancy->id);
        $this->assertModelData($fakeTenancy, $updatedTenancy->toArray());
        $dbTenancy = $this->tenancyRepo->find($tenancy->id);
        $this->assertModelData($fakeTenancy, $dbTenancy->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteTenancy()
    {
        $tenancy = $this->makeTenancy();
        $resp = $this->tenancyRepo->delete($tenancy->id);
        $this->assertTrue($resp);
        $this->assertNull(Tenancy::find($tenancy->id), 'Tenancy should not exist in DB');
    }
}
