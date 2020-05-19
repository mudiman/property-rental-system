<?php

use App\Models\Agent;
use App\Repositories\AgentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AgentRepositoryTest extends TestCase
{
    use MakeAgentTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AgentRepository
     */
    protected $agentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->agentRepo = App::make(AgentRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAgent()
    {
        $agent = $this->fakeAgentData();
        $createdAgent = $this->agentRepo->create($agent);
        $createdAgent = $createdAgent->toArray();
        $this->assertArrayHasKey('id', $createdAgent);
        $this->assertNotNull($createdAgent['id'], 'Created Agent must have id specified');
        $this->assertNotNull(Agent::find($createdAgent['id']), 'Agent with given id must be in DB');
        $this->assertModelData($agent, $createdAgent);
    }

    /**
     * @test read
     */
    public function testReadAgent()
    {
        $agent = $this->makeAgent();
        $dbAgent = $this->agentRepo->find($agent->id);
        $dbAgent = $dbAgent->toArray();
        $this->assertModelData($agent->toArray(), $dbAgent);
    }

    /**
     * @test update
     */
    public function testUpdateAgent()
    {
        $agent = $this->makeAgent();
        $fakeAgent = $this->fakeAgentData();
        $updatedAgent = $this->agentRepo->update($fakeAgent, $agent->id);
        $this->assertModelData($fakeAgent, $updatedAgent->toArray());
        $dbAgent = $this->agentRepo->find($agent->id);
        $this->assertModelData($fakeAgent, $dbAgent->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAgent()
    {
        $agent = $this->makeAgent();
        $resp = $this->agentRepo->delete($agent->id);
        $this->assertTrue($resp);
        $this->assertNull(Agent::find($agent->id), 'Agent should not exist in DB');
    }
}
