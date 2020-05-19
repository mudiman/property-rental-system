<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BathroomTypeApiTest extends TestCase
{
    use MakeBathroomTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateBathroomType()
    {
        $bathroomType = $this->fakeBathroomTypeData();
        $this->json('POST', '/api/v1/bathroomTypes', $bathroomType);

        $this->assertApiResponse($bathroomType);
    }

    /**
     * @test
     */
    public function testReadBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $this->json('GET', '/api/v1/bathroomTypes/'.$bathroomType->id);

        $this->assertApiResponse($bathroomType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $editedBathroomType = $this->fakeBathroomTypeData();

        $this->json('PUT', '/api/v1/bathroomTypes/'.$bathroomType->id, $editedBathroomType);

        $this->assertApiResponse($editedBathroomType);
    }

    /**
     * @test
     */
    public function testDeleteBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $this->json('DELETE', '/api/v1/bathroomTypes/'.$bathroomType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/bathroomTypes/'.$bathroomType->id);

        $this->assertResponseStatus(404);
    }
}
