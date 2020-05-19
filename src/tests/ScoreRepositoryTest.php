<?php

use App\Models\Score;
use App\Repositories\ScoreRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreRepositoryTest extends TestCase
{
    use MakeScoreTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ScoreRepository
     */
    protected $scoreRepo;

    public function setUp()
    {
        parent::setUp();
        $this->scoreRepo = App::make(ScoreRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateScore()
    {
        $score = $this->fakeScoreData();
        $createdScore = $this->scoreRepo->create($score);
        $createdScore = $createdScore->toArray();
        $this->assertArrayHasKey('id', $createdScore);
        $this->assertNotNull($createdScore['id'], 'Created Score must have id specified');
        $this->assertNotNull(Score::find($createdScore['id']), 'Score with given id must be in DB');
        $this->assertModelData($score, $createdScore);
    }

    /**
     * @test read
     */
    public function testReadScore()
    {
        $score = $this->makeScore();
        $dbScore = $this->scoreRepo->find($score->id);
        $dbScore = $dbScore->toArray();
        $this->assertModelData($score->toArray(), $dbScore);
    }

    /**
     * @test update
     */
    public function testUpdateScore()
    {
        $score = $this->makeScore();
        $fakeScore = $this->fakeScoreData();
        $updatedScore = $this->scoreRepo->update($fakeScore, $score->id);
        $this->assertModelData($fakeScore, $updatedScore->toArray());
        $dbScore = $this->scoreRepo->find($score->id);
        $this->assertModelData($fakeScore, $dbScore->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteScore()
    {
        $score = $this->makeScore();
        $resp = $this->scoreRepo->delete($score->id);
        $this->assertTrue($resp);
        $this->assertNull(Score::find($score->id), 'Score should not exist in DB');
    }
}
