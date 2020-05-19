<?php

use App\Models\PropertyService;
use App\Repositories\PropertyServiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyServiceRepositoryTest extends TestCase
{
    use MakePropertyServiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PropertyServiceRepository
     */
    protected $propertyServiceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->propertyServiceRepo = App::make(PropertyServiceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePropertyService()
    {
        $propertyService = $this->fakePropertyServiceData();
        $createdPropertyService = $this->propertyServiceRepo->create($propertyService);
        $createdPropertyService = $createdPropertyService->toArray();
        $this->assertArrayHasKey('id', $createdPropertyService);
        $this->assertNotNull($createdPropertyService['id'], 'Created PropertyService must have id specified');
        $this->assertNotNull(PropertyService::find($createdPropertyService['id']), 'PropertyService with given id must be in DB');
        $this->assertModelData($propertyService, $createdPropertyService);
    }

    /**
     * @test read
     */
    public function testReadPropertyService()
    {
        $propertyService = $this->makePropertyService();
        $dbPropertyService = $this->propertyServiceRepo->find($propertyService->id);
        $dbPropertyService = $dbPropertyService->toArray();
        $this->assertModelData($propertyService->toArray(), $dbPropertyService);
    }

    /**
     * @test update
     */
    public function testUpdatePropertyService()
    {
        $propertyService = $this->makePropertyService();
        $fakePropertyService = $this->fakePropertyServiceData();
        $updatedPropertyService = $this->propertyServiceRepo->update($fakePropertyService, $propertyService->id);
        $this->assertModelData($fakePropertyService, $updatedPropertyService->toArray());
        $dbPropertyService = $this->propertyServiceRepo->find($propertyService->id);
        $this->assertModelData($fakePropertyService, $dbPropertyService->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePropertyService()
    {
        $propertyService = $this->makePropertyService();
        $resp = $this->propertyServiceRepo->delete($propertyService->id);
        $this->assertTrue($resp);
        $this->assertNull(PropertyService::find($propertyService->id), 'PropertyService should not exist in DB');
    }
}
