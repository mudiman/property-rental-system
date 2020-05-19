<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadApiTest extends TestCase
{
    use MakeThreadTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateThread()
    {
        $thread = $this->fakeThreadData();
        $this->json('POST', '/api/v1/threads', $thread);

        $this->assertApiResponse($thread);
    }

    /**
     * @test
     */
    public function testReadThread()
    {
        $thread = $this->makeThread();
        $this->json('GET', '/api/v1/threads/'.$thread->id);

        $this->assertApiResponse($thread->toArray());
    }

    /**
     * @test
     */
    public function testUpdateThread()
    {
        $thread = $this->makeThread();
        $editedThread = $this->fakeThreadData();

        $this->json('PUT', '/api/v1/threads/'.$thread->id, $editedThread);

        $this->assertApiResponse($editedThread);
    }

    /**
     * @test
     */
    public function testDeleteThread()
    {
        $thread = $this->makeThread();
        $this->json('DELETE', '/api/v1/threads/'.$thread->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/threads/'.$thread->id);

        $this->assertResponseStatus(404);
    }
}
