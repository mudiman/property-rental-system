<?php

use Faker\Factory as Faker;
use App\Models\BathroomType;
use App\Repositories\BathroomTypeRepository;

trait MakeBathroomTypeTrait
{
    /**
     * Create fake instance of BathroomType and save it in database
     *
     * @param array $bathroomTypeFields
     * @return BathroomType
     */
    public function makeBathroomType($bathroomTypeFields = [])
    {
        /** @var BathroomTypeRepository $bathroomTypeRepo */
        $bathroomTypeRepo = App::make(BathroomTypeRepository::class);
        $theme = $this->fakeBathroomTypeData($bathroomTypeFields);
        return $bathroomTypeRepo->create($theme);
    }

    /**
     * Get fake instance of BathroomType
     *
     * @param array $bathroomTypeFields
     * @return BathroomType
     */
    public function fakeBathroomType($bathroomTypeFields = [])
    {
        return new BathroomType($this->fakeBathroomTypeData($bathroomTypeFields));
    }

    /**
     * Get fake data of BathroomType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeBathroomTypeData($bathroomTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $bathroomTypeFields);
    }
}
