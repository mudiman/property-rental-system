<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayinApiTest extends TestCase
{
    use MakePayinTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePayin()
    {
        $payin = $this->fakePayinData();
        $this->json('POST', '/api/v1/payins', $payin);

        $this->assertApiResponse($payin);
    }

    /**
     * @test
     */
    public function testReadPayin()
    {
        $payin = $this->makePayin();
        $this->json('GET', '/api/v1/payins/'.$payin->id);

        $this->assertApiResponse($payin->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePayin()
    {
        $payin = $this->makePayin();
        $editedPayin = $this->fakePayinData();

        $this->json('PUT', '/api/v1/payins/'.$payin->id, $editedPayin);

        $this->assertApiResponse($editedPayin);
    }

    /**
     * @test
     */
    public function testDeletePayin()
    {
        $payin = $this->makePayin();
        $this->json('DELETE', '/api/v1/payins/'.$payin->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/payins/'.$payin->id);

        $this->assertResponseStatus(404);
    }
}
