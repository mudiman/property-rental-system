<?php

use Faker\Factory as Faker;
use App\Models\LettingType;
use App\Repositories\LettingTypeRepository;

trait MakeLettingTypeTrait
{
    /**
     * Create fake instance of LettingType and save it in database
     *
     * @param array $lettingTypeFields
     * @return LettingType
     */
    public function makeLettingType($lettingTypeFields = [])
    {
        /** @var LettingTypeRepository $lettingTypeRepo */
        $lettingTypeRepo = App::make(LettingTypeRepository::class);
        $theme = $this->fakeLettingTypeData($lettingTypeFields);
        return $lettingTypeRepo->create($theme);
    }

    /**
     * Get fake instance of LettingType
     *
     * @param array $lettingTypeFields
     * @return LettingType
     */
    public function fakeLettingType($lettingTypeFields = [])
    {
        return new LettingType($this->fakeLettingTypeData($lettingTypeFields));
    }

    /**
     * Get fake data of LettingType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeLettingTypeData($lettingTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'icon' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $lettingTypeFields);
    }
}
