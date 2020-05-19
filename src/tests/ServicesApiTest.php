<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicesApiTest extends TestCase
{
    use MakeServicesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateServices()
    {
        $services = $this->fakeServicesData();
        $this->json('POST', '/api/v1/services', $services);

        $this->assertApiResponse($services);
    }

    /**
     * @test
     */
    public function testReadServices()
    {
        $services = $this->makeServices();
        $this->json('GET', '/api/v1/services/'.$services->id);

        $this->assertApiResponse($services->toArray());
    }

    /**
     * @test
     */
    public function testUpdateServices()
    {
        $services = $this->makeServices();
        $editedServices = $this->fakeServicesData();

        $this->json('PUT', '/api/v1/services/'.$services->id, $editedServices);

        $this->assertApiResponse($editedServices);
    }

    /**
     * @test
     */
    public function testDeleteServices()
    {
        $services = $this->makeServices();
        $this->json('DELETE', '/api/v1/services/'.$services->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/services/'.$services->id);

        $this->assertResponseStatus(404);
    }
}
