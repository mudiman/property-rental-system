<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionApiTest extends TestCase
{
    use MakeTransactionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateTransaction()
    {
        $transaction = $this->fakeTransactionData();
        $this->json('POST', '/api/v1/transactions', $transaction);

        $this->assertApiResponse($transaction);
    }

    /**
     * @test
     */
    public function testReadTransaction()
    {
        $transaction = $this->makeTransaction();
        $this->json('GET', '/api/v1/transactions/'.$transaction->id);

        $this->assertApiResponse($transaction->toArray());
    }

    /**
     * @test
     */
    public function testUpdateTransaction()
    {
        $transaction = $this->makeTransaction();
        $editedTransaction = $this->fakeTransactionData();

        $this->json('PUT', '/api/v1/transactions/'.$transaction->id, $editedTransaction);

        $this->assertApiResponse($editedTransaction);
    }

    /**
     * @test
     */
    public function testDeleteTransaction()
    {
        $transaction = $this->makeTransaction();
        $this->json('DELETE', '/api/v1/transactions/'.$transaction->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/transactions/'.$transaction->id);

        $this->assertResponseStatus(404);
    }
}
