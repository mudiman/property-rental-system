<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TenancyApiTest extends TestCase
{
    use MakeTenancyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateTenancy()
    {
        $tenancy = $this->fakeTenancyData();
        $this->json('POST', '/api/v1/tenancies', $tenancy);

        $this->assertApiResponse($tenancy);
    }

    /**
     * @test
     */
    public function testReadTenancy()
    {
        $tenancy = $this->makeTenancy();
        $this->json('GET', '/api/v1/tenancies/'.$tenancy->id);

        $this->assertApiResponse($tenancy->toArray());
    }

    /**
     * @test
     */
    public function testUpdateTenancy()
    {
        $tenancy = $this->makeTenancy();
        $editedTenancy = $this->fakeTenancyData();

        $this->json('PUT', '/api/v1/tenancies/'.$tenancy->id, $editedTenancy);

        $this->assertApiResponse($editedTenancy);
    }

    /**
     * @test
     */
    public function testDeleteTenancy()
    {
        $tenancy = $this->makeTenancy();
        $this->json('DELETE', '/api/v1/tenancies/'.$tenancy->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/tenancies/'.$tenancy->id);

        $this->assertResponseStatus(404);
    }
}
