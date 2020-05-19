<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyServiceApiTest extends TestCase
{
    use MakePropertyServiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePropertyService()
    {
        $propertyService = $this->fakePropertyServiceData();
        $this->json('POST', '/api/v1/propertyServices', $propertyService);

        $this->assertApiResponse($propertyService);
    }

    /**
     * @test
     */
    public function testReadPropertyService()
    {
        $propertyService = $this->makePropertyService();
        $this->json('GET', '/api/v1/propertyServices/'.$propertyService->id);

        $this->assertApiResponse($propertyService->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePropertyService()
    {
        $propertyService = $this->makePropertyService();
        $editedPropertyService = $this->fakePropertyServiceData();

        $this->json('PUT', '/api/v1/propertyServices/'.$propertyService->id, $editedPropertyService);

        $this->assertApiResponse($editedPropertyService);
    }

    /**
     * @test
     */
    public function testDeletePropertyService()
    {
        $propertyService = $this->makePropertyService();
        $this->json('DELETE', '/api/v1/propertyServices/'.$propertyService->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/propertyServices/'.$propertyService->id);

        $this->assertResponseStatus(404);
    }
}
