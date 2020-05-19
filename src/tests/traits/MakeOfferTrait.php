<?php

use Faker\Factory as Faker;
use App\Models\Offer;
use App\Repositories\OfferRepository;

trait MakeOfferTrait
{
    /**
     * Create fake instance of Offer and save it in database
     *
     * @param array $offerFields
     * @return Offer
     */
    public function makeOffer($offerFields = [])
    {
        /** @var OfferRepository $offerRepo */
        $offerRepo = App::make(OfferRepository::class);
        $theme = $this->fakeOfferData($offerFields);
        return $offerRepo->create($theme);
    }

    /**
     * Get fake instance of Offer
     *
     * @param array $offerFields
     * @return Offer
     */
    public function fakeOffer($offerFields = [])
    {
        return new Offer($this->fakeOfferData($offerFields));
    }

    /**
     * Get fake data of Offer
     *
     * @param array $postFields
     * @return array
     */
    public function fakeOfferData($offerFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'thread' => $fake->word,
            'tenant_id' => $fake->randomDigitNotNull,
            'property_id' => $fake->randomDigitNotNull,
            'landlord_id' => $fake->randomDigitNotNull,
            'property_pro_id' => $fake->randomDigitNotNull,
            'previous_offer_id' => $fake->randomDigitNotNull,
            'payout_id' => $fake->randomDigitNotNull,
            'landlord_payin_id' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'type' => $fake->word,
            'offer_expiry' => $fake->date('Y-m-d H:i:s'),
            'holding_deposit_expiry' => $fake->date('Y-m-d H:i:s'),
            'checkin' => $fake->word,
            'checkout' => $fake->word,
            'actual_checkin' => $fake->date('Y-m-d H:i:s'),
            'actual_checkout' => $fake->date('Y-m-d H:i:s'),
            'rent_per_month' => $fake->randomDigitNotNull,
            'rent_per_week' => $fake->randomDigitNotNull,
            'rent_per_night' => $fake->randomDigitNotNull,
            'currency' => $fake->word,
            'security_deposit_week' => $fake->randomDigitNotNull,
            'security_deposit_amount' => $fake->randomDigitNotNull,
            'special_condition' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $offerFields);
    }
}
