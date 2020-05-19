<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HistoryApiTest extends TestCase
{
    use MakeHistoryTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateHistory()
    {
        $history = $this->fakeHistoryData();
        $this->json('POST', '/api/v1/histories', $history);

        $this->assertApiResponse($history);
    }

    /**
     * @test
     */
    public function testReadHistory()
    {
        $history = $this->makeHistory();
        $this->json('GET', '/api/v1/histories/'.$history->id);

        $this->assertApiResponse($history->toArray());
    }

    /**
     * @test
     */
    public function testUpdateHistory()
    {
        $history = $this->makeHistory();
        $editedHistory = $this->fakeHistoryData();

        $this->json('PUT', '/api/v1/histories/'.$history->id, $editedHistory);

        $this->assertApiResponse($editedHistory);
    }

    /**
     * @test
     */
    public function testDeleteHistory()
    {
        $history = $this->makeHistory();
        $this->json('DELETE', '/api/v1/histories/'.$history->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/histories/'.$history->id);

        $this->assertResponseStatus(404);
    }
}
