<?php

use Faker\Factory as Faker;
use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;

trait MakePaymentMethodTrait
{
    /**
     * Create fake instance of PaymentMethod and save it in database
     *
     * @param array $paymentMethodFields
     * @return PaymentMethod
     */
    public function makePaymentMethod($paymentMethodFields = [])
    {
        /** @var PaymentMethodRepository $paymentMethodRepo */
        $paymentMethodRepo = App::make(PaymentMethodRepository::class);
        $theme = $this->fakePaymentMethodData($paymentMethodFields);
        return $paymentMethodRepo->create($theme);
    }

    /**
     * Get fake instance of PaymentMethod
     *
     * @param array $paymentMethodFields
     * @return PaymentMethod
     */
    public function fakePaymentMethod($paymentMethodFields = [])
    {
        return new PaymentMethod($this->fakePaymentMethodData($paymentMethodFields));
    }

    /**
     * Get fake data of PaymentMethod
     *
     * @param array $postFields
     * @return array
     */
    public function fakePaymentMethodData($paymentMethodFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $paymentMethodFields);
    }
}
