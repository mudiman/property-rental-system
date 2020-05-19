<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceFeeTypeApiTest extends TestCase
{
    use MakeServiceFeeTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateServiceFeeType()
    {
        $serviceFeeType = $this->fakeServiceFeeTypeData();
        $this->json('POST', '/api/v1/serviceFeeTypes', $serviceFeeType);

        $this->assertApiResponse($serviceFeeType);
    }

    /**
     * @test
     */
    public function testReadServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $this->json('GET', '/api/v1/serviceFeeTypes/'.$serviceFeeType->id);

        $this->assertApiResponse($serviceFeeType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $editedServiceFeeType = $this->fakeServiceFeeTypeData();

        $this->json('PUT', '/api/v1/serviceFeeTypes/'.$serviceFeeType->id, $editedServiceFeeType);

        $this->assertApiResponse($editedServiceFeeType);
    }

    /**
     * @test
     */
    public function testDeleteServiceFeeType()
    {
        $serviceFeeType = $this->makeServiceFeeType();
        $this->json('DELETE', '/api/v1/serviceFeeTypes/'.$serviceFeeType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/serviceFeeTypes/'.$serviceFeeType->id);

        $this->assertResponseStatus(404);
    }
}
