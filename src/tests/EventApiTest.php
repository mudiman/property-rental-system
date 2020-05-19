<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventApiTest extends TestCase
{
    use MakeEventTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateEvent()
    {
        $event = $this->fakeEventData();
        $this->json('POST', '/api/v1/events', $event);

        $this->assertApiResponse($event);
    }

    /**
     * @test
     */
    public function testReadEvent()
    {
        $event = $this->makeEvent();
        $this->json('GET', '/api/v1/events/'.$event->id);

        $this->assertApiResponse($event->toArray());
    }

    /**
     * @test
     */
    public function testUpdateEvent()
    {
        $event = $this->makeEvent();
        $editedEvent = $this->fakeEventData();

        $this->json('PUT', '/api/v1/events/'.$event->id, $editedEvent);

        $this->assertApiResponse($editedEvent);
    }

    /**
     * @test
     */
    public function testDeleteEvent()
    {
        $event = $this->makeEvent();
        $this->json('DELETE', '/api/v1/events/'.$event->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/events/'.$event->id);

        $this->assertResponseStatus(404);
    }
}
