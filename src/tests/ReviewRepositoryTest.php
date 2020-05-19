<?php

use App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewRepositoryTest extends TestCase
{
    use MakeReviewTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ReviewRepository
     */
    protected $reviewRepo;

    public function setUp()
    {
        parent::setUp();
        $this->reviewRepo = App::make(ReviewRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateReview()
    {
        $review = $this->fakeReviewData();
        $createdReview = $this->reviewRepo->create($review);
        $createdReview = $createdReview->toArray();
        $this->assertArrayHasKey('id', $createdReview);
        $this->assertNotNull($createdReview['id'], 'Created Review must have id specified');
        $this->assertNotNull(Review::find($createdReview['id']), 'Review with given id must be in DB');
        $this->assertModelData($review, $createdReview);
    }

    /**
     * @test read
     */
    public function testReadReview()
    {
        $review = $this->makeReview();
        $dbReview = $this->reviewRepo->find($review->id);
        $dbReview = $dbReview->toArray();
        $this->assertModelData($review->toArray(), $dbReview);
    }

    /**
     * @test update
     */
    public function testUpdateReview()
    {
        $review = $this->makeReview();
        $fakeReview = $this->fakeReviewData();
        $updatedReview = $this->reviewRepo->update($fakeReview, $review->id);
        $this->assertModelData($fakeReview, $updatedReview->toArray());
        $dbReview = $this->reviewRepo->find($review->id);
        $this->assertModelData($fakeReview, $dbReview->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteReview()
    {
        $review = $this->makeReview();
        $resp = $this->reviewRepo->delete($review->id);
        $this->assertTrue($resp);
        $this->assertNull(Review::find($review->id), 'Review should not exist in DB');
    }
}
