<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceTypeApiTest extends TestCase
{
    use MakeServiceTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateServiceType()
    {
        $serviceType = $this->fakeServiceTypeData();
        $this->json('POST', '/api/v1/serviceTypes', $serviceType);

        $this->assertApiResponse($serviceType);
    }

    /**
     * @test
     */
    public function testReadServiceType()
    {
        $serviceType = $this->makeServiceType();
        $this->json('GET', '/api/v1/serviceTypes/'.$serviceType->id);

        $this->assertApiResponse($serviceType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateServiceType()
    {
        $serviceType = $this->makeServiceType();
        $editedServiceType = $this->fakeServiceTypeData();

        $this->json('PUT', '/api/v1/serviceTypes/'.$serviceType->id, $editedServiceType);

        $this->assertApiResponse($editedServiceType);
    }

    /**
     * @test
     */
    public function testDeleteServiceType()
    {
        $serviceType = $this->makeServiceType();
        $this->json('DELETE', '/api/v1/serviceTypes/'.$serviceType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/serviceTypes/'.$serviceType->id);

        $this->assertResponseStatus(404);
    }
}
