<?php

use Faker\Factory as Faker;
use App\Models\PropertyRoomType;
use App\Repositories\PropertyRoomTypeRepository;

trait MakePropertyRoomTypeTrait
{
    /**
     * Create fake instance of PropertyRoomType and save it in database
     *
     * @param array $propertyRoomTypeFields
     * @return PropertyRoomType
     */
    public function makePropertyRoomType($propertyRoomTypeFields = [])
    {
        /** @var PropertyRoomTypeRepository $propertyRoomTypeRepo */
        $propertyRoomTypeRepo = App::make(PropertyRoomTypeRepository::class);
        $theme = $this->fakePropertyRoomTypeData($propertyRoomTypeFields);
        return $propertyRoomTypeRepo->create($theme);
    }

    /**
     * Get fake instance of PropertyRoomType
     *
     * @param array $propertyRoomTypeFields
     * @return PropertyRoomType
     */
    public function fakePropertyRoomType($propertyRoomTypeFields = [])
    {
        return new PropertyRoomType($this->fakePropertyRoomTypeData($propertyRoomTypeFields));
    }

    /**
     * Get fake data of PropertyRoomType
     *
     * @param array $postFields
     * @return array
     */
    public function fakePropertyRoomTypeData($propertyRoomTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'icon' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $propertyRoomTypeFields);
    }
}
