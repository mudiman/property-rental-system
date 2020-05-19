<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LettingTypeApiTest extends TestCase
{
    use MakeLettingTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLettingType()
    {
        $lettingType = $this->fakeLettingTypeData();
        $this->json('POST', '/api/v1/lettingTypes', $lettingType);

        $this->assertApiResponse($lettingType);
    }

    /**
     * @test
     */
    public function testReadLettingType()
    {
        $lettingType = $this->makeLettingType();
        $this->json('GET', '/api/v1/lettingTypes/'.$lettingType->id);

        $this->assertApiResponse($lettingType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLettingType()
    {
        $lettingType = $this->makeLettingType();
        $editedLettingType = $this->fakeLettingTypeData();

        $this->json('PUT', '/api/v1/lettingTypes/'.$lettingType->id, $editedLettingType);

        $this->assertApiResponse($editedLettingType);
    }

    /**
     * @test
     */
    public function testDeleteLettingType()
    {
        $lettingType = $this->makeLettingType();
        $this->json('DELETE', '/api/v1/lettingTypes/'.$lettingType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/lettingTypes/'.$lettingType->id);

        $this->assertResponseStatus(404);
    }
}
