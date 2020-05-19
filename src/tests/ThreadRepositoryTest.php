<?php

use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadRepositoryTest extends TestCase
{
    use MakeThreadTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ThreadRepository
     */
    protected $threadRepo;

    public function setUp()
    {
        parent::setUp();
        $this->threadRepo = App::make(ThreadRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateThread()
    {
        $thread = $this->fakeThreadData();
        $createdThread = $this->threadRepo->create($thread);
        $createdThread = $createdThread->toArray();
        $this->assertArrayHasKey('id', $createdThread);
        $this->assertNotNull($createdThread['id'], 'Created Thread must have id specified');
        $this->assertNotNull(Thread::find($createdThread['id']), 'Thread with given id must be in DB');
        $this->assertModelData($thread, $createdThread);
    }

    /**
     * @test read
     */
    public function testReadThread()
    {
        $thread = $this->makeThread();
        $dbThread = $this->threadRepo->find($thread->id);
        $dbThread = $dbThread->toArray();
        $this->assertModelData($thread->toArray(), $dbThread);
    }

    /**
     * @test update
     */
    public function testUpdateThread()
    {
        $thread = $this->makeThread();
        $fakeThread = $this->fakeThreadData();
        $updatedThread = $this->threadRepo->update($fakeThread, $thread->id);
        $this->assertModelData($fakeThread, $updatedThread->toArray());
        $dbThread = $this->threadRepo->find($thread->id);
        $this->assertModelData($fakeThread, $dbThread->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteThread()
    {
        $thread = $this->makeThread();
        $resp = $this->threadRepo->delete($thread->id);
        $this->assertTrue($resp);
        $this->assertNull(Thread::find($thread->id), 'Thread should not exist in DB');
    }
}
