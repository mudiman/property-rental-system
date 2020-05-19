<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTypeApiTest extends TestCase
{
    use MakeScoreTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateScoreType()
    {
        $scoreType = $this->fakeScoreTypeData();
        $this->json('POST', '/api/v1/scoreTypes', $scoreType);

        $this->assertApiResponse($scoreType);
    }

    /**
     * @test
     */
    public function testReadScoreType()
    {
        $scoreType = $this->makeScoreType();
        $this->json('GET', '/api/v1/scoreTypes/'.$scoreType->id);

        $this->assertApiResponse($scoreType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateScoreType()
    {
        $scoreType = $this->makeScoreType();
        $editedScoreType = $this->fakeScoreTypeData();

        $this->json('PUT', '/api/v1/scoreTypes/'.$scoreType->id, $editedScoreType);

        $this->assertApiResponse($editedScoreType);
    }

    /**
     * @test
     */
    public function testDeleteScoreType()
    {
        $scoreType = $this->makeScoreType();
        $this->json('DELETE', '/api/v1/scoreTypes/'.$scoreType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/scoreTypes/'.$scoreType->id);

        $this->assertResponseStatus(404);
    }
}
