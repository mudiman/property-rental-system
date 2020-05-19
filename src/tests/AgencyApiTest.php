<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AgencyApiTest extends TestCase
{
    use MakeAgencyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAgency()
    {
        $agency = $this->fakeAgencyData();
        $this->json('POST', '/api/v1/agencies', $agency);

        $this->assertApiResponse($agency);
    }

    /**
     * @test
     */
    public function testReadAgency()
    {
        $agency = $this->makeAgency();
        $this->json('GET', '/api/v1/agencies/'.$agency->id);

        $this->assertApiResponse($agency->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAgency()
    {
        $agency = $this->makeAgency();
        $editedAgency = $this->fakeAgencyData();

        $this->json('PUT', '/api/v1/agencies/'.$agency->id, $editedAgency);

        $this->assertApiResponse($editedAgency);
    }

    /**
     * @test
     */
    public function testDeleteAgency()
    {
        $agency = $this->makeAgency();
        $this->json('DELETE', '/api/v1/agencies/'.$agency->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/agencies/'.$agency->id);

        $this->assertResponseStatus(404);
    }
}
