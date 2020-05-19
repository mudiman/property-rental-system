<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyProApiTest extends TestCase
{
    use MakePropertyProTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePropertyPro()
    {
        $propertyPro = $this->fakePropertyProData();
        $this->json('POST', '/api/v1/propertyPros', $propertyPro);

        $this->assertApiResponse($propertyPro);
    }

    /**
     * @test
     */
    public function testReadPropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $this->json('GET', '/api/v1/propertyPros/'.$propertyPro->id);

        $this->assertApiResponse($propertyPro->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $editedPropertyPro = $this->fakePropertyProData();

        $this->json('PUT', '/api/v1/propertyPros/'.$propertyPro->id, $editedPropertyPro);

        $this->assertApiResponse($editedPropertyPro);
    }

    /**
     * @test
     */
    public function testDeletePropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $this->json('DELETE', '/api/v1/propertyPros/'.$propertyPro->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/propertyPros/'.$propertyPro->id);

        $this->assertResponseStatus(404);
    }
}
