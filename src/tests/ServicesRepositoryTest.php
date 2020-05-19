<?php

use App\Models\Services;
use App\Repositories\ServicesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicesRepositoryTest extends TestCase
{
    use MakeServicesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ServicesRepository
     */
    protected $servicesRepo;

    public function setUp()
    {
        parent::setUp();
        $this->servicesRepo = App::make(ServicesRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateServices()
    {
        $services = $this->fakeServicesData();
        $createdServices = $this->servicesRepo->create($services);
        $createdServices = $createdServices->toArray();
        $this->assertArrayHasKey('id', $createdServices);
        $this->assertNotNull($createdServices['id'], 'Created Services must have id specified');
        $this->assertNotNull(Services::find($createdServices['id']), 'Services with given id must be in DB');
        $this->assertModelData($services, $createdServices);
    }

    /**
     * @test read
     */
    public function testReadServices()
    {
        $services = $this->makeServices();
        $dbServices = $this->servicesRepo->find($services->id);
        $dbServices = $dbServices->toArray();
        $this->assertModelData($services->toArray(), $dbServices);
    }

    /**
     * @test update
     */
    public function testUpdateServices()
    {
        $services = $this->makeServices();
        $fakeServices = $this->fakeServicesData();
        $updatedServices = $this->servicesRepo->update($fakeServices, $services->id);
        $this->assertModelData($fakeServices, $updatedServices->toArray());
        $dbServices = $this->servicesRepo->find($services->id);
        $this->assertModelData($fakeServices, $dbServices->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteServices()
    {
        $services = $this->makeServices();
        $resp = $this->servicesRepo->delete($services->id);
        $this->assertTrue($resp);
        $this->assertNull(Services::find($services->id), 'Services should not exist in DB');
    }
}
