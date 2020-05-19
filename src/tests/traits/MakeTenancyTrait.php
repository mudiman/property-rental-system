<?php

use Faker\Factory as Faker;
use App\Models\Tenancy;
use App\Repositories\TenancyRepository;

trait MakeTenancyTrait
{
    /**
     * Create fake instance of Tenancy and save it in database
     *
     * @param array $tenancyFields
     * @return Tenancy
     */
    public function makeTenancy($tenancyFields = [])
    {
        /** @var TenancyRepository $tenancyRepo */
        $tenancyRepo = App::make(TenancyRepository::class);
        $theme = $this->fakeTenancyData($tenancyFields);
        return $tenancyRepo->create($theme);
    }

    /**
     * Get fake instance of Tenancy
     *
     * @param array $tenancyFields
     * @return Tenancy
     */
    public function fakeTenancy($tenancyFields = [])
    {
        return new Tenancy($this->fakeTenancyData($tenancyFields));
    }

    /**
     * Get fake data of Tenancy
     *
     * @param array $postFields
     * @return array
     */
    public function fakeTenancyData($tenancyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'tenant_id' => $fake->word,
            'property_id' => $fake->word,
            'offer_id' => $fake->word,
            'thread' => $fake->word,
            'parent_tenancy_id' => $fake->word,
            'landlord_id' => $fake->word,
            'property_pro_id' => $fake->word,
            'payout_id' => $fake->word,
            'landlord_payin_id' => $fake->word,
            'status' => $fake->word,
            'mode' => $fake->word,
            'previous_status' => $fake->word,
            'type' => $fake->word,
            'sign_expiry' => $fake->date('Y-m-d H:i:s'),
            'checkin' => $fake->word,
            'checkout' => $fake->word,
            'actual_checkin' => $fake->date('Y-m-d H:i:s'),
            'actual_checkout' => $fake->date('Y-m-d H:i:s'),
            'due_date' => $fake->word,
            'due_amount' => $fake->randomDigitNotNull,
            'tenant_sign_location' => $fake->word,
            'tenant_sign_datetime' => $fake->date('Y-m-d H:i:s'),
            'landlord_sign_location' => $fake->word,
            'landlord_sign_datetime' => $fake->date('Y-m-d H:i:s'),
            'special_condition' => $fake->text,
            'landlord_notice_reminded' => $fake->word,
            'tenant_notice_reminded' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $tenancyFields);
    }
}
