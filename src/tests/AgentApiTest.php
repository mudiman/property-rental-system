<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AgentApiTest extends TestCase
{
    use MakeAgentTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAgent()
    {
        $agent = $this->fakeAgentData();
        $this->json('POST', '/api/v1/agents', $agent);

        $this->assertApiResponse($agent);
    }

    /**
     * @test
     */
    public function testReadAgent()
    {
        $agent = $this->makeAgent();
        $this->json('GET', '/api/v1/agents/'.$agent->id);

        $this->assertApiResponse($agent->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAgent()
    {
        $agent = $this->makeAgent();
        $editedAgent = $this->fakeAgentData();

        $this->json('PUT', '/api/v1/agents/'.$agent->id, $editedAgent);

        $this->assertApiResponse($editedAgent);
    }

    /**
     * @test
     */
    public function testDeleteAgent()
    {
        $agent = $this->makeAgent();
        $this->json('DELETE', '/api/v1/agents/'.$agent->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/agents/'.$agent->id);

        $this->assertResponseStatus(404);
    }
}
