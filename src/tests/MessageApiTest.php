<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessageApiTest extends TestCase
{
    use MakeMessageTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMessage()
    {
        $message = $this->fakeMessageData();
        $this->json('POST', '/api/v1/messages', $message);

        $this->assertApiResponse($message);
    }

    /**
     * @test
     */
    public function testReadMessage()
    {
        $message = $this->makeMessage();
        $this->json('GET', '/api/v1/messages/'.$message->id);

        $this->assertApiResponse($message->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMessage()
    {
        $message = $this->makeMessage();
        $editedMessage = $this->fakeMessageData();

        $this->json('PUT', '/api/v1/messages/'.$message->id, $editedMessage);

        $this->assertApiResponse($editedMessage);
    }

    /**
     * @test
     */
    public function testDeleteMessage()
    {
        $message = $this->makeMessage();
        $this->json('DELETE', '/api/v1/messages/'.$message->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/messages/'.$message->id);

        $this->assertResponseStatus(404);
    }
}
