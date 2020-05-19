<?php

use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentMethodRepositoryTest extends TestCase
{
    use MakePaymentMethodTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepo;

    public function setUp()
    {
        parent::setUp();
        $this->paymentMethodRepo = App::make(PaymentMethodRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePaymentMethod()
    {
        $paymentMethod = $this->fakePaymentMethodData();
        $createdPaymentMethod = $this->paymentMethodRepo->create($paymentMethod);
        $createdPaymentMethod = $createdPaymentMethod->toArray();
        $this->assertArrayHasKey('id', $createdPaymentMethod);
        $this->assertNotNull($createdPaymentMethod['id'], 'Created PaymentMethod must have id specified');
        $this->assertNotNull(PaymentMethod::find($createdPaymentMethod['id']), 'PaymentMethod with given id must be in DB');
        $this->assertModelData($paymentMethod, $createdPaymentMethod);
    }

    /**
     * @test read
     */
    public function testReadPaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $dbPaymentMethod = $this->paymentMethodRepo->find($paymentMethod->id);
        $dbPaymentMethod = $dbPaymentMethod->toArray();
        $this->assertModelData($paymentMethod->toArray(), $dbPaymentMethod);
    }

    /**
     * @test update
     */
    public function testUpdatePaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $fakePaymentMethod = $this->fakePaymentMethodData();
        $updatedPaymentMethod = $this->paymentMethodRepo->update($fakePaymentMethod, $paymentMethod->id);
        $this->assertModelData($fakePaymentMethod, $updatedPaymentMethod->toArray());
        $dbPaymentMethod = $this->paymentMethodRepo->find($paymentMethod->id);
        $this->assertModelData($fakePaymentMethod, $dbPaymentMethod->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePaymentMethod()
    {
        $paymentMethod = $this->makePaymentMethod();
        $resp = $this->paymentMethodRepo->delete($paymentMethod->id);
        $this->assertTrue($resp);
        $this->assertNull(PaymentMethod::find($paymentMethod->id), 'PaymentMethod should not exist in DB');
    }
}
