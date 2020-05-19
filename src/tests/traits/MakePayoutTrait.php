<?php

use Faker\Factory as Faker;
use App\Models\Payout;
use App\Repositories\PayoutRepository;

trait MakePayoutTrait
{
    /**
     * Create fake instance of Payout and save it in database
     *
     * @param array $payoutFields
     * @return Payout
     */
    public function makePayout($payoutFields = [])
    {
        /** @var PayoutRepository $payoutRepo */
        $payoutRepo = App::make(PayoutRepository::class);
        $theme = $this->fakePayoutData($payoutFields);
        return $payoutRepo->create($theme);
    }

    /**
     * Get fake instance of Payout
     *
     * @param array $payoutFields
     * @return Payout
     */
    public function fakePayout($payoutFields = [])
    {
        return new Payout($this->fakePayoutData($payoutFields));
    }

    /**
     * Get fake data of Payout
     *
     * @param array $postFields
     * @return array
     */
    public function fakePayoutData($payoutFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'payment_method' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'holder_name' => $fake->word,
            'card_number' => $fake->word,
            'expire_on_month' => $fake->randomDigitNotNull,
            'expire_on_year' => $fake->randomDigitNotNull,
            'expiry' => $fake->word,
            'security_code' => $fake->word,
            'country' => $fake->word,
            'payout_data' => $fake->text,
            'payout_reference' => $fake->word,
            'smoor_reference' => $fake->word,
            'user_reference' => $fake->word,
            'token' => $fake->word,
            'default' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $payoutFields);
    }
}
