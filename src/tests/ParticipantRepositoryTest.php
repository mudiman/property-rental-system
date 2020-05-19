<?php

use App\Models\Participant;
use App\Repositories\ParticipantRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipantRepositoryTest extends TestCase
{
    use MakeParticipantTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ParticipantRepository
     */
    protected $participantRepo;

    public function setUp()
    {
        parent::setUp();
        $this->participantRepo = App::make(ParticipantRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateParticipant()
    {
        $participant = $this->fakeParticipantData();
        $createdParticipant = $this->participantRepo->create($participant);
        $createdParticipant = $createdParticipant->toArray();
        $this->assertArrayHasKey('id', $createdParticipant);
        $this->assertNotNull($createdParticipant['id'], 'Created Participant must have id specified');
        $this->assertNotNull(Participant::find($createdParticipant['id']), 'Participant with given id must be in DB');
        $this->assertModelData($participant, $createdParticipant);
    }

    /**
     * @test read
     */
    public function testReadParticipant()
    {
        $participant = $this->makeParticipant();
        $dbParticipant = $this->participantRepo->find($participant->id);
        $dbParticipant = $dbParticipant->toArray();
        $this->assertModelData($participant->toArray(), $dbParticipant);
    }

    /**
     * @test update
     */
    public function testUpdateParticipant()
    {
        $participant = $this->makeParticipant();
        $fakeParticipant = $this->fakeParticipantData();
        $updatedParticipant = $this->participantRepo->update($fakeParticipant, $participant->id);
        $this->assertModelData($fakeParticipant, $updatedParticipant->toArray());
        $dbParticipant = $this->participantRepo->find($participant->id);
        $this->assertModelData($fakeParticipant, $dbParticipant->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteParticipant()
    {
        $participant = $this->makeParticipant();
        $resp = $this->participantRepo->delete($participant->id);
        $this->assertTrue($resp);
        $this->assertNull(Participant::find($participant->id), 'Participant should not exist in DB');
    }
}
