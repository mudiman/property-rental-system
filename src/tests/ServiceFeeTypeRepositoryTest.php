<?php

use App\Models\ServiceFeeType;
use App\Repositories\ServiceFeeTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceFeeTypeRepositoryTest extends TestCase
{
    use MakeServiceFeeTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ServiceFeeTypeRepository
     */
    protected $serviceFeeTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->serviceFeeTypeRepo = App::make(ServiceFeeTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateServiceFeeType()
    {
        $serviceFeeType = $this->fakeServiceFeeTypeData();
        $createdServiceFeeType = $this->serviceFeeTypeRepo->create($serviceFeeType);
        $createdServiceFeeType = $createdServiceFeeType->toArray();
        $this->assertArrayHasKey('id', $createdServiceFeeType);
        $this->assertNotNull($createdServiceFeeType['id'], 'Created ServiceFeeType must have id specified');
        $this->assertNotNull(ServiceFeeType::find($createdServiceFeeType['id']), 'ServiceFeeType with given id must be in DB');
        $this->assertModelData($serviceFeeType, $createdServiceFeeType);
    }

    /**
     * @test read
     */
    public function testReadServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $dbServiceFeeType = $this->serviceFeeTypeRepo->find($serviceFeeType->id);
        $dbServiceFeeType = $dbServiceFeeType->toArray();
        $this->assertModelData($serviceFeeType->toArray(), $dbServiceFeeType);
    }

    /**
     * @test update
     */
    public function testUpdateServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $fakeServiceFeeType = $this->fakeServiceFeeTypeData();
        $updatedServiceFeeType = $this->serviceFeeTypeRepo->update($fakeServiceFeeType, $serviceFeeType->id);
        $this->assertModelData($fakeServiceFeeType, $updatedServiceFeeType->toArray());
        $dbServiceFeeType = $this->serviceFeeTypeRepo->find($serviceFeeType->id);
        $this->assertModelData($fakeServiceFeeType, $dbServiceFeeType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $resp = $this->serviceFeeTypeRepo->delete($serviceFeeType->id);
        $this->assertTrue($resp);
        $this->assertNull(ServiceFeeType::find($serviceFeeType->id), 'ServiceFeeType should not exist in DB');
    }
}
