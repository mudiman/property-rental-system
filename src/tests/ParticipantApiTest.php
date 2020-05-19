<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipantApiTest extends TestCase
{
    use MakeParticipantTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateParticipant()
    {
        $participant = $this->fakeParticipantData();
        $this->json('POST', '/api/v1/participants', $participant);

        $this->assertApiResponse($participant);
    }

    /**
     * @test
     */
    public function testReadParticipant()
    {
        $participant = $this->makeParticipant();
        $this->json('GET', '/api/v1/participants/'.$participant->id);

        $this->assertApiResponse($participant->toArray());
    }

    /**
     * @test
     */
    public function testUpdateParticipant()
    {
        $participant = $this->makeParticipant();
        $editedParticipant = $this->fakeParticipantData();

        $this->json('PUT', '/api/v1/participants/'.$participant->id, $editedParticipant);

        $this->assertApiResponse($editedParticipant);
    }

    /**
     * @test
     */
    public function testDeleteParticipant()
    {
        $participant = $this->makeParticipant();
        $this->json('DELETE', '/api/v1/participants/'.$participant->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/participants/'.$participant->id);

        $this->assertResponseStatus(404);
    }
}
