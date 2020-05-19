<?php

use Faker\Factory as Faker;
use App\Models\Payin;
use App\Repositories\PayinRepository;

trait MakePayinTrait
{
    /**
     * Create fake instance of Payin and save it in database
     *
     * @param array $payinFields
     * @return Payin
     */
    public function makePayin($payinFields = [])
    {
        /** @var PayinRepository $payinRepo */
        $payinRepo = App::make(PayinRepository::class);
        $theme = $this->fakePayinData($payinFields);
        return $payinRepo->create($theme);
    }

    /**
     * Get fake instance of Payin
     *
     * @param array $payinFields
     * @return Payin
     */
    public function fakePayin($payinFields = [])
    {
        return new Payin($this->fakePayinData($payinFields));
    }

    /**
     * Get fake data of Payin
     *
     * @param array $postFields
     * @return array
     */
    public function fakePayinData($payinFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'bank_name' => $fake->word,
            'account_number' => $fake->word,
            'routing_number' => $fake->word,
            'currency' => $fake->word,
            'iban' => $fake->word,
            'countryCode' => $fake->word,
            'sort_code' => $fake->word,
            'bic' => $fake->word,
            'ip' => $fake->word,
            'first_name' => $fake->word,
            'last_name' => $fake->word,
            'email' => $fake->word,
            'phone' => $fake->word,
            'gender' => $fake->word,
            'date_of_birth' => $fake->word,
            'ssn' => $fake->word,
            'address' => $fake->word,
            'legal_name' => $fake->word,
            'tax_id' => $fake->word,
            'locality' => $fake->word,
            'postal_code' => $fake->word,
            'region' => $fake->word,
            'entity_type' => $fake->word,
            'nationality' => $fake->word,
            'billing_address' => $fake->text,
            'payin_data' => $fake->text,
            'smoor_reference' => $fake->word,
            'user_reference' => $fake->word,
            'reference' => $fake->word,
            'token' => $fake->word,
            'verified' => $fake->word,
            'payment_gateway_response' => $fake->text,
            'verification_response' => $fake->text,
            'default' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $payinFields);
    }
}
