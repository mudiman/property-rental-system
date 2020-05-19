<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewingApiTest extends TestCase
{
    use MakeViewingTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateViewing()
    {
        $viewing = $this->fakeViewingData();
        $this->json('POST', '/api/v1/viewings', $viewing);

        $this->assertApiResponse($viewing);
    }

    /**
     * @test
     */
    public function testReadViewing()
    {
        $viewing = $this->makeViewing();
        $this->json('GET', '/api/v1/viewings/'.$viewing->id);

        $this->assertApiResponse($viewing->toArray());
    }

    /**
     * @test
     */
    public function testUpdateViewing()
    {
        $viewing = $this->makeViewing();
        $editedViewing = $this->fakeViewingData();

        $this->json('PUT', '/api/v1/viewings/'.$viewing->id, $editedViewing);

        $this->assertApiResponse($editedViewing);
    }

    /**
     * @test
     */
    public function testDeleteViewing()
    {
        $viewing = $this->makeViewing();
        $this->json('DELETE', '/api/v1/viewings/'.$viewing->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/viewings/'.$viewing->id);

        $this->assertResponseStatus(404);
    }
}
