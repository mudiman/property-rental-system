<?php

use App\Models\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceRepositoryTest extends TestCase
{
    use MakeServiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ServiceRepository
     */
    protected $serviceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->serviceRepo = App::make(ServiceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateService()
    {
        $service = $this->fakeServiceData();
        $createdService = $this->serviceRepo->create($service);
        $createdService = $createdService->toArray();
        $this->assertArrayHasKey('id', $createdService);
        $this->assertNotNull($createdService['id'], 'Created Service must have id specified');
        $this->assertNotNull(Service::find($createdService['id']), 'Service with given id must be in DB');
        $this->assertModelData($service, $createdService);
    }

    /**
     * @test read
     */
    public function testReadService()
    {
        $service = $this->makeService();
        $dbService = $this->serviceRepo->find($service->id);
        $dbService = $dbService->toArray();
        $this->assertModelData($service->toArray(), $dbService);
    }

    /**
     * @test update
     */
    public function testUpdateService()
    {
        $service = $this->makeService();
        $fakeService = $this->fakeServiceData();
        $updatedService = $this->serviceRepo->update($fakeService, $service->id);
        $this->assertModelData($fakeService, $updatedService->toArray());
        $dbService = $this->serviceRepo->find($service->id);
        $this->assertModelData($fakeService, $dbService->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteService()
    {
        $service = $this->makeService();
        $resp = $this->serviceRepo->delete($service->id);
        $this->assertTrue($resp);
        $this->assertNull(Service::find($service->id), 'Service should not exist in DB');
    }
}
