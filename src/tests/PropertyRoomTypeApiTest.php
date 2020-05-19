<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyRoomTypeApiTest extends TestCase
{
    use MakePropertyRoomTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePropertyRoomType()
    {
        $propertyRoomType = $this->fakePropertyRoomTypeData();
        $this->json('POST', '/api/v1/propertyRoomTypes', $propertyRoomType);

        $this->assertApiResponse($propertyRoomType);
    }

    /**
     * @test
     */
    public function testReadPropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $this->json('GET', '/api/v1/propertyRoomTypes/'.$propertyRoomType->id);

        $this->assertApiResponse($propertyRoomType->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $editedPropertyRoomType = $this->fakePropertyRoomTypeData();

        $this->json('PUT', '/api/v1/propertyRoomTypes/'.$propertyRoomType->id, $editedPropertyRoomType);

        $this->assertApiResponse($editedPropertyRoomType);
    }

    /**
     * @test
     */
    public function testDeletePropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $this->json('DELETE', '/api/v1/propertyRoomTypes/'.$propertyRoomType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/propertyRoomTypes/'.$propertyRoomType->id);

        $this->assertResponseStatus(404);
    }
}
