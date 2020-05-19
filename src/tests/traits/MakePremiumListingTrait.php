<?php

use Faker\Factory as Faker;
use App\Models\PremiumListing;
use App\Repositories\PremiumListingRepository;

trait MakePremiumListingTrait
{
    /**
     * Create fake instance of PremiumListing and save it in database
     *
     * @param array $premiumListingFields
     * @return PremiumListing
     */
    public function makePremiumListing($premiumListingFields = [])
    {
        /** @var PremiumListingRepository $premiumListingRepo */
        $premiumListingRepo = App::make(PremiumListingRepository::class);
        $theme = $this->fakePremiumListingData($premiumListingFields);
        return $premiumListingRepo->create($theme);
    }

    /**
     * Get fake instance of PremiumListing
     *
     * @param array $premiumListingFields
     * @return PremiumListing
     */
    public function fakePremiumListing($premiumListingFields = [])
    {
        return new PremiumListing($this->fakePremiumListingData($premiumListingFields));
    }

    /**
     * Get fake data of PremiumListing
     *
     * @param array $postFields
     * @return array
     */
    public function fakePremiumListingData($premiumListingFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'type' => $fake->word,
            'property_id' => $fake->randomDigitNotNull,
            'end_datetime' => $fake->date('Y-m-d H:i:s'),
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $premiumListingFields);
    }
}
