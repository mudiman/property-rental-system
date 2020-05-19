<?php

use Faker\Factory as Faker;
use App\Models\ScoreType;
use App\Repositories\ScoreTypeRepository;

trait MakeScoreTypeTrait
{
    /**
     * Create fake instance of ScoreType and save it in database
     *
     * @param array $scoreTypeFields
     * @return ScoreType
     */
    public function makeScoreType($scoreTypeFields = [])
    {
        /** @var ScoreTypeRepository $scoreTypeRepo */
        $scoreTypeRepo = App::make(ScoreTypeRepository::class);
        $theme = $this->fakeScoreTypeData($scoreTypeFields);
        return $scoreTypeRepo->create($theme);
    }

    /**
     * Get fake instance of ScoreType
     *
     * @param array $scoreTypeFields
     * @return ScoreType
     */
    public function fakeScoreType($scoreTypeFields = [])
    {
        return new ScoreType($this->fakeScoreTypeData($scoreTypeFields));
    }

    /**
     * Get fake data of ScoreType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeScoreTypeData($scoreTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'category' => $fake->word,
            'roles' => $fake->word,
            'weight' => $fake->randomDigitNotNull,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $scoreTypeFields);
    }
}
