<?php

use Faker\Factory as Faker;
use App\Models\Participant;
use App\Repositories\ParticipantRepository;

trait MakeParticipantTrait
{
    /**
     * Create fake instance of Participant and save it in database
     *
     * @param array $participantFields
     * @return Participant
     */
    public function makeParticipant($participantFields = [])
    {
        /** @var ParticipantRepository $participantRepo */
        $participantRepo = App::make(ParticipantRepository::class);
        $theme = $this->fakeParticipantData($participantFields);
        return $participantRepo->create($theme);
    }

    /**
     * Get fake instance of Participant
     *
     * @param array $participantFields
     * @return Participant
     */
    public function fakeParticipant($participantFields = [])
    {
        return new Participant($this->fakeParticipantData($participantFields));
    }

    /**
     * Get fake data of Participant
     *
     * @param array $postFields
     * @return array
     */
    public function fakeParticipantData($participantFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'thread_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull
        ], $participantFields);
    }
}
