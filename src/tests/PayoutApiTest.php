<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayoutApiTest extends TestCase
{
    use MakePayoutTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePayout()
    {
        $payout = $this->fakePayoutData();
        $this->json('POST', '/api/v1/payouts', $payout);

        $this->assertApiResponse($payout);
    }

    /**
     * @test
     */
    public function testReadPayout()
    {
        $payout = $this->makePayout();
        $this->json('GET', '/api/v1/payouts/'.$payout->id);

        $this->assertApiResponse($payout->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePayout()
    {
        $payout = $this->makePayout();
        $editedPayout = $this->fakePayoutData();

        $this->json('PUT', '/api/v1/payouts/'.$payout->id, $editedPayout);

        $this->assertApiResponse($editedPayout);
    }

    /**
     * @test
     */
    public function testDeletePayout()
    {
        $payout = $this->makePayout();
        $this->json('DELETE', '/api/v1/payouts/'.$payout->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/payouts/'.$payout->id);

        $this->assertResponseStatus(404);
    }
}
