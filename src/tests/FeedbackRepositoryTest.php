<?php

use App\Models\Feedback;
use App\Repositories\FeedbackRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeedbackRepositoryTest extends TestCase
{
    use MakeFeedbackTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FeedbackRepository
     */
    protected $feedbackRepo;

    public function setUp()
    {
        parent::setUp();
        $this->feedbackRepo = App::make(FeedbackRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFeedback()
    {
        $feedback = $this->fakeFeedbackData();
        $createdFeedback = $this->feedbackRepo->create($feedback);
        $createdFeedback = $createdFeedback->toArray();
        $this->assertArrayHasKey('id', $createdFeedback);
        $this->assertNotNull($createdFeedback['id'], 'Created Feedback must have id specified');
        $this->assertNotNull(Feedback::find($createdFeedback['id']), 'Feedback with given id must be in DB');
        $this->assertModelData($feedback, $createdFeedback);
    }

    /**
     * @test read
     */
    public function testReadFeedback()
    {
        $feedback = $this->makeFeedback();
        $dbFeedback = $this->feedbackRepo->find($feedback->id);
        $dbFeedback = $dbFeedback->toArray();
        $this->assertModelData($feedback->toArray(), $dbFeedback);
    }

    /**
     * @test update
     */
    public function testUpdateFeedback()
    {
        $feedback = $this->makeFeedback();
        $fakeFeedback = $this->fakeFeedbackData();
        $updatedFeedback = $this->feedbackRepo->update($fakeFeedback, $feedback->id);
        $this->assertModelData($fakeFeedback, $updatedFeedback->toArray());
        $dbFeedback = $this->feedbackRepo->find($feedback->id);
        $this->assertModelData($fakeFeedback, $dbFeedback->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFeedback()
    {
        $feedback = $this->makeFeedback();
        $resp = $this->feedbackRepo->delete($feedback->id);
        $this->assertTrue($resp);
        $this->assertNull(Feedback::find($feedback->id), 'Feedback should not exist in DB');
    }
}
