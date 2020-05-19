<?php

use Faker\Factory as Faker;
use App\Models\PropertyPro;
use App\Repositories\PropertyProRepository;

trait MakePropertyProTrait
{
    /**
     * Create fake instance of PropertyPro and save it in database
     *
     * @param array $propertyProFields
     * @return PropertyPro
     */
    public function makePropertyPro($propertyProFields = [])
    {
        /** @var PropertyProRepository $propertyProRepo */
        $propertyProRepo = App::make(PropertyProRepository::class);
        $theme = $this->fakePropertyProData($propertyProFields);
        return $propertyProRepo->create($theme);
    }

    /**
     * Get fake instance of PropertyPro
     *
     * @param array $propertyProFields
     * @return PropertyPro
     */
    public function fakePropertyPro($propertyProFields = [])
    {
        return new PropertyPro($this->fakePropertyProData($propertyProFields));
    }

    /**
     * Get fake data of PropertyPro
     *
     * @param array $postFields
     * @return array
     */
    public function fakePropertyProData($propertyProFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'thread' => $fake->word,
            'landlord_id' => $fake->word,
            'property_pro_id' => $fake->word,
            'property_id' => $fake->word,
            'property_pro_payin_id' => $fake->word,
            'property_pro_sign_location' => $fake->word,
            'property_pro_sign_datetime' => $fake->date('Y-m-d H:i:s'),
            'landlord_sign_location' => $fake->word,
            'landlord_sign_datetime' => $fake->date('Y-m-d H:i:s'),
            'price_type' => $fake->word,
            'price' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $propertyProFields);
    }
}
