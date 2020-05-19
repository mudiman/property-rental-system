<?php

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventRepositoryTest extends TestCase
{
    use MakeEventTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var EventRepository
     */
    protected $eventRepo;

    public function setUp()
    {
        parent::setUp();
        $this->eventRepo = App::make(EventRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateEvent()
    {
        $event = $this->fakeEventData();
        $createdEvent = $this->eventRepo->create($event);
        $createdEvent = $createdEvent->toArray();
        $this->assertArrayHasKey('id', $createdEvent);
        $this->assertNotNull($createdEvent['id'], 'Created Event must have id specified');
        $this->assertNotNull(Event::find($createdEvent['id']), 'Event with given id must be in DB');
        $this->assertModelData($event, $createdEvent);
    }

    /**
     * @test read
     */
    public function testReadEvent()
    {
        $event = $this->makeEvent();
        $dbEvent = $this->eventRepo->find($event->id);
        $dbEvent = $dbEvent->toArray();
        $this->assertModelData($event->toArray(), $dbEvent);
    }

    /**
     * @test update
     */
    public function testUpdateEvent()
    {
        $event = $this->makeEvent();
        $fakeEvent = $this->fakeEventData();
        $updatedEvent = $this->eventRepo->update($fakeEvent, $event->id);
        $this->assertModelData($fakeEvent, $updatedEvent->toArray());
        $dbEvent = $this->eventRepo->find($event->id);
        $this->assertModelData($fakeEvent, $dbEvent->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteEvent()
    {
        $event = $this->makeEvent();
        $resp = $this->eventRepo->delete($event->id);
        $this->assertTrue($resp);
        $this->assertNull(Event::find($event->id), 'Event should not exist in DB');
    }
}
