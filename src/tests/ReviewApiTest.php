<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewApiTest extends TestCase
{
    use MakeReviewTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateReview()
    {
        $review = $this->fakeReviewData();
        $this->json('POST', '/api/v1/reviews', $review);

        $this->assertApiResponse($review);
    }

    /**
     * @test
     */
    public function testReadReview()
    {
        $review = $this->makeReview();
        $this->json('GET', '/api/v1/reviews/'.$review->id);

        $this->assertApiResponse($review->toArray());
    }

    /**
     * @test
     */
    public function testUpdateReview()
    {
        $review = $this->makeReview();
        $editedReview = $this->fakeReviewData();

        $this->json('PUT', '/api/v1/reviews/'.$review->id, $editedReview);

        $this->assertApiResponse($editedReview);
    }

    /**
     * @test
     */
    public function testDeleteReview()
    {
        $review = $this->makeReview();
        $this->json('DELETE', '/api/v1/reviews/'.$review->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/reviews/'.$review->id);

        $this->assertResponseStatus(404);
    }
}
