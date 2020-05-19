<?php

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessageRepositoryTest extends TestCase
{
    use MakeMessageTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MessageRepository
     */
    protected $messageRepo;

    public function setUp()
    {
        parent::setUp();
        $this->messageRepo = App::make(MessageRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMessage()
    {
        $message = $this->fakeMessageData();
        $createdMessage = $this->messageRepo->create($message);
        $createdMessage = $createdMessage->toArray();
        $this->assertArrayHasKey('id', $createdMessage);
        $this->assertNotNull($createdMessage['id'], 'Created Message must have id specified');
        $this->assertNotNull(Message::find($createdMessage['id']), 'Message with given id must be in DB');
        $this->assertModelData($message, $createdMessage);
    }

    /**
     * @test read
     */
    public function testReadMessage()
    {
        $message = $this->makeMessage();
        $dbMessage = $this->messageRepo->find($message->id);
        $dbMessage = $dbMessage->toArray();
        $this->assertModelData($message->toArray(), $dbMessage);
    }

    /**
     * @test update
     */
    public function testUpdateMessage()
    {
        $message = $this->makeMessage();
        $fakeMessage = $this->fakeMessageData();
        $updatedMessage = $this->messageRepo->update($fakeMessage, $message->id);
        $this->assertModelData($fakeMessage, $updatedMessage->toArray());
        $dbMessage = $this->messageRepo->find($message->id);
        $this->assertModelData($fakeMessage, $dbMessage->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMessage()
    {
        $message = $this->makeMessage();
        $resp = $this->messageRepo->delete($message->id);
        $this->assertTrue($resp);
        $this->assertNull(Message::find($message->id), 'Message should not exist in DB');
    }
}
