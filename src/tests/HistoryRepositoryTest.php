<?php

use App\Models\History;
use App\Repositories\HistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HistoryRepositoryTest extends TestCase
{
    use MakeHistoryTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var HistoryRepository
     */
    protected $historyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->historyRepo = App::make(HistoryRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateHistory()
    {
        $history = $this->fakeHistoryData();
        $createdHistory = $this->historyRepo->create($history);
        $createdHistory = $createdHistory->toArray();
        $this->assertArrayHasKey('id', $createdHistory);
        $this->assertNotNull($createdHistory['id'], 'Created History must have id specified');
        $this->assertNotNull(History::find($createdHistory['id']), 'History with given id must be in DB');
        $this->assertModelData($history, $createdHistory);
    }

    /**
     * @test read
     */
    public function testReadHistory()
    {
        $history = $this->makeHistory();
        $dbHistory = $this->historyRepo->find($history->id);
        $dbHistory = $dbHistory->toArray();
        $this->assertModelData($history->toArray(), $dbHistory);
    }

    /**
     * @test update
     */
    public function testUpdateHistory()
    {
        $history = $this->makeHistory();
        $fakeHistory = $this->fakeHistoryData();
        $updatedHistory = $this->historyRepo->update($fakeHistory, $history->id);
        $this->assertModelData($fakeHistory, $updatedHistory->toArray());
        $dbHistory = $this->historyRepo->find($history->id);
        $this->assertModelData($fakeHistory, $dbHistory->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteHistory()
    {
        $history = $this->makeHistory();
        $resp = $this->historyRepo->delete($history->id);
        $this->assertTrue($resp);
        $this->assertNull(History::find($history->id), 'History should not exist in DB');
    }
}
