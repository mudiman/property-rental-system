<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyApiTest extends TestCase
{
    use MakePropertyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateProperty()
    {
        $property = $this->fakePropertyData();
        $this->json('POST', '/api/v1/properties', $property);

        $this->assertApiResponse($property);
    }

    /**
     * @test
     */
    public function testReadProperty()
    {
        $property = $this->makeProperty();
        $this->json('GET', '/api/v1/properties/'.$property->id);

        $this->assertApiResponse($property->toArray());
    }

    /**
     * @test
     */
    public function testUpdateProperty()
    {
        $property = $this->makeProperty();
        $editedProperty = $this->fakePropertyData();

        $this->json('PUT', '/api/v1/properties/'.$property->id, $editedProperty);

        $this->assertApiResponse($editedProperty);
    }

    /**
     * @test
     */
    public function testDeleteProperty()
    {
        $property = $this->makeProperty();
        $this->json('DELETE', '/api/v1/properties/'.$property->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/properties/'.$property->id);

        $this->assertResponseStatus(404);
    }
}
