<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentMethodApiTest extends TestCase
{
    use MakePaymentMethodTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePaymentMethod()
    {
        $paymentMethod = $this->fakePaymentMethodData();
        $this->json('POST', '/api/v1/paymentMethods', $paymentMethod);

        $this->assertApiResponse($paymentMethod);
    }

    /**
     * @test
     */
    public function testReadPaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $this->json('GET', '/api/v1/paymentMethods/'.$paymentMethod->id);

        $this->assertApiResponse($paymentMethod->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $editedPaymentMethod = $this->fakePaymentMethodData();

        $this->json('PUT', '/api/v1/paymentMethods/'.$paymentMethod->id, $editedPaymentMethod);

        $this->assertApiResponse($editedPaymentMethod);
    }

    /**
     * @test
     */
    public function testDeletePaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $this->json('DELETE', '/api/v1/paymentMethods/'.$paymentMethod->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/paymentMethods/'.$paymentMethod->id);

        $this->assertResponseStatus(404);
    }
}
