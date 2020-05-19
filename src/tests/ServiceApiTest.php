<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceApiTest extends TestCase
{
    use MakeServiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateService()
    {
        $service = $this->fakeServiceData();
        $this->json('POST', '/api/v1/services', $service);

        $this->assertApiResponse($service);
    }

    /**
     * @test
     */
    public function testReadService()
    {
        $service = $this->makeService();
        $this->json('GET', '/api/v1/services/'.$service->id);

        $this->assertApiResponse($service->toArray());
    }

    /**
     * @test
     */
    public function testUpdateService()
    {
        $service = $this->makeService();
        $editedService = $this->fakeServiceData();

        $this->json('PUT', '/api/v1/services/'.$service->id, $editedService);

        $this->assertApiResponse($editedService);
    }

    /**
     * @test
     */
    public function testDeleteService()
    {
        $service = $this->makeService();
        $this->json('DELETE', '/api/v1/services/'.$service->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/services/'.$service->id);

        $this->assertResponseStatus(404);
    }
}
