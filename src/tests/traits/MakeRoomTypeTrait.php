<?php

use Faker\Factory as Faker;
use App\Models\RoomType;
use App\Repositories\RoomTypeRepository;

trait MakeRoomTypeTrait
{
    /**
     * Create fake instance of RoomType and save it in database
     *
     * @param array $roomTypeFields
     * @return RoomType
     */
    public function makeRoomType($roomTypeFields = [])
    {
        /** @var RoomTypeRepository $roomTypeRepo */
        $roomTypeRepo = App::make(RoomTypeRepository::class);
        $theme = $this->fakeRoomTypeData($roomTypeFields);
        return $roomTypeRepo->create($theme);
    }

    /**
     * Get fake instance of RoomType
     *
     * @param array $roomTypeFields
     * @return RoomType
     */
    public function fakeRoomType($roomTypeFields = [])
    {
        return new RoomType($this->fakeRoomTypeData($roomTypeFields));
    }

    /**
     * Get fake data of RoomType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeRoomTypeData($roomTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $roomTypeFields);
    }
}
