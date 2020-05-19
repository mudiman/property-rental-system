<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreApiTest extends TestCase
{
    use MakeScoreTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateScore()
    {
        $score = $this->fakeScoreData();
        $this->json('POST', '/api/v1/scores', $score);

        $this->assertApiResponse($score);
    }

    /**
     * @test
     */
    public function testReadScore()
    {
        $score = $this->makeScore();
        $this->json('GET', '/api/v1/scores/'.$score->id);

        $this->assertApiResponse($score->toArray());
    }

    /**
     * @test
     */
    public function testUpdateScore()
    {
        $score = $this->makeScore();
        $editedScore = $this->fakeScoreData();

        $this->json('PUT', '/api/v1/scores/'.$score->id, $editedScore);

        $this->assertApiResponse($editedScore);
    }

    /**
     * @test
     */
    public function testDeleteScore()
    {
        $score = $this->makeScore();
        $this->json('DELETE', '/api/v1/scores/'.$score->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/scores/'.$score->id);

        $this->assertResponseStatus(404);
    }
}
