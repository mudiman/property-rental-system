<?php

use Faker\Factory as Faker;
use App\Models\Property;
use App\Repositories\PropertyRepository;

trait MakePropertyTrait
{
    /**
     * Create fake instance of Property and save it in database
     *
     * @param array $propertyFields
     * @return Property
     */
    public function makeProperty($propertyFields = [])
    {
        /** @var PropertyRepository $propertyRepo */
        $propertyRepo = App::make(PropertyRepository::class);
        $theme = $this->fakePropertyData($propertyFields);
        return $propertyRepo->create($theme);
    }

    /**
     * Get fake instance of Property
     *
     * @param array $propertyFields
     * @return Property
     */
    public function fakeProperty($propertyFields = [])
    {
        return new Property($this->fakePropertyData($propertyFields));
    }

    /**
     * Get fake data of Property
     *
     * @param array $postFields
     * @return array
     */
    public function fakePropertyData($propertyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'landlord_id' => $fake->word,
            'profile_picture' => $fake->word,
            'reference' => $fake->word,
            'title' => $fake->word,
            'summary' => $fake->word,
            'letting_type' => $fake->word,
            'property_type' => $fake->word,
            'room_type' => $fake->word,
            'room_suitable' => $fake->word,
            'bathroom_type' => $fake->word,
            'people_living' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'completion_phase' => $fake->word,
            'available_date' => $fake->word,
            'cordinate' => $fake->word,
            'postcode' => $fake->word,
            'door_number' => $fake->word,
            'street' => $fake->word,
            'city' => $fake->word,
            'verified' => $fake->word,
            'apartment_building' => $fake->word,
            'floors' => $fake->randomDigitNotNull,
            'floor' => $fake->randomDigitNotNull,
            'county' => $fake->word,
            'country' => $fake->word,
            'currency' => $fake->word,
            'rent_per_month' => $fake->randomDigitNotNull,
            'rent_per_week' => $fake->randomDigitNotNull,
            'rent_per_night' => $fake->randomDigitNotNull,
            'minimum_accepted_price' => $fake->randomDigitNotNull,
            'minimum_accepted_price_short_term_price' => $fake->randomDigitNotNull,
            'security_deposit_weeks' => $fake->randomDigitNotNull,
            'security_deposit_amount' => $fake->randomDigitNotNull,
            'security_deposit_holding_amount' => $fake->randomDigitNotNull,
            'contract_length_months' => $fake->randomDigitNotNull,
            'shortterm_rent_per_month' => $fake->randomDigitNotNull,
            'shortterm_rent_per_week' => $fake->randomDigitNotNull,
            'valuation_comment' => $fake->word,
            'valuation_rating' => $fake->randomDigitNotNull,
            'quick_booking' => $fake->word,
            'area_overview' => $fake->word,
            'area_info' => $fake->word,
            'notes' => $fake->text,
            'rules' => $fake->word,
            'getting_around' => $fake->word,
            'receptions' => $fake->randomDigitNotNull,
            'bedrooms' => $fake->randomDigitNotNull,
            'bathrooms' => $fake->randomDigitNotNull,
            'has_garden' => $fake->word,
            'has_balcony_terrace' => $fake->word,
            'has_parking' => $fake->word,
            'ensuite' => $fake->word,
            'flatshares' => $fake->word,
            'reviewed' => $fake->word,
            'total_listing_view' => $fake->randomDigitNotNull,
            'total_detail_view' => $fake->randomDigitNotNull,
            'view_history' => $fake->text,
            'extra_info' => $fake->text,
            'inclusive' => $fake->word,
            'parent_property_id' => $fake->word,
            'data' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $propertyFields);
    }
}
