<?php

use Faker\Factory as Faker;
use App\Models\Agent;
use App\Repositories\AgentRepository;

trait MakeAgentTrait
{
    /**
     * Create fake instance of Agent and save it in database
     *
     * @param array $agentFields
     * @return Agent
     */
    public function makeAgent($agentFields = [])
    {
        /** @var AgentRepository $agentRepo */
        $agentRepo = App::make(AgentRepository::class);
        $theme = $this->fakeAgentData($agentFields);
        return $agentRepo->create($theme);
    }

    /**
     * Get fake instance of Agent
     *
     * @param array $agentFields
     * @return Agent
     */
    public function fakeAgent($agentFields = [])
    {
        return new Agent($this->fakeAgentData($agentFields));
    }

    /**
     * Get fake data of Agent
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAgentData($agentFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'agency_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $agentFields);
    }
}
