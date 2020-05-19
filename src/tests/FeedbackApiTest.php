<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeedbackApiTest extends TestCase
{
    use MakeFeedbackTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFeedback()
    {
        $feedback = $this->fakeFeedbackData();
        $this->json('POST', '/api/v1/feedback', $feedback);

        $this->assertApiResponse($feedback);
    }

    /**
     * @test
     */
    public function testReadFeedback()
    {
        $feedback = $this->makeFeedback();
        $this->json('GET', '/api/v1/feedback/'.$feedback->id);

        $this->assertApiResponse($feedback->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFeedback()
    {
        $feedback = $this->makeFeedback();
        $editedFeedback = $this->fakeFeedbackData();

        $this->json('PUT', '/api/v1/feedback/'.$feedback->id, $editedFeedback);

        $this->assertApiResponse($editedFeedback);
    }

    /**
     * @test
     */
    public function testDeleteFeedback()
    {
        $feedback = $this->makeFeedback();
        $this->json('DELETE', '/api/v1/feedback/'.$feedback->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/feedback/'.$feedback->id);

        $this->assertResponseStatus(404);
    }
}
