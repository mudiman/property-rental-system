<?php

use App\Models\ServiceType;
use App\Repositories\ServiceTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceTypeRepositoryTest extends TestCase
{
    use MakeServiceTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ServiceTypeRepository
     */
    protected $serviceTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->serviceTypeRepo = App::make(ServiceTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateServiceType()
    {
        $serviceType = $this->fakeServiceTypeData();
        $createdServiceType = $this->serviceTypeRepo->create($serviceType);
        $createdServiceType = $createdServiceType->toArray();
        $this->assertArrayHasKey('id', $createdServiceType);
        $this->assertNotNull($createdServiceType['id'], 'Created ServiceType must have id specified');
        $this->assertNotNull(ServiceType::find($createdServiceType['id']), 'ServiceType with given id must be in DB');
        $this->assertModelData($serviceType, $createdServiceType);
    }

    /**
     * @test read
     */
    public function testReadServiceType()
    {
        $serviceType = $this->makeServiceType();
        $dbServiceType = $this->serviceTypeRepo->find($serviceType->id);
        $dbServiceType = $dbServiceType->toArray();
        $this->assertModelData($serviceType->toArray(), $dbServiceType);
    }

    /**
     * @test update
     */
    public function testUpdateServiceType()
    {
        $serviceType = $this->makeServiceType();
        $fakeServiceType = $this->fakeServiceTypeData();
        $updatedServiceType = $this->serviceTypeRepo->update($fakeServiceType, $serviceType->id);
        $this->assertModelData($fakeServiceType, $updatedServiceType->toArray());
        $dbServiceType = $this->serviceTypeRepo->find($serviceType->id);
        $this->assertModelData($fakeServiceType, $dbServiceType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteServiceType()
    {
        $serviceType = $this->makeServiceType();
        $resp = $this->serviceTypeRepo->delete($serviceType->id);
        $this->assertTrue($resp);
        $this->assertNull(ServiceType::find($serviceType->id), 'ServiceType should not exist in DB');
    }
}
