<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewingRequestApiTest extends TestCase
{
    use MakeViewingRequestTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateViewingRequest()
    {
        $viewingRequest = $this->fakeViewingRequestData();
        $this->json('POST', '/api/v1/viewingRequests', $viewingRequest);

        $this->assertApiResponse($viewingRequest);
    }

    /**
     * @test
     */
    public function testReadViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $this->json('GET', '/api/v1/viewingRequests/'.$viewingRequest->id);

        $this->assertApiResponse($viewingRequest->toArray());
    }

    /**
     * @test
     */
    public function testUpdateViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $editedViewingRequest = $this->fakeViewingRequestData();

        $this->json('PUT', '/api/v1/viewingRequests/'.$viewingRequest->id, $editedViewingRequest);

        $this->assertApiResponse($editedViewingRequest);
    }

    /**
     * @test
     */
    public function testDeleteViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $this->json('DELETE', '/api/v1/viewingRequests/'.$viewingRequest->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/viewingRequests/'.$viewingRequest->id);

        $this->assertResponseStatus(404);
    }
}
