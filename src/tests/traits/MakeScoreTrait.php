<?php

use Faker\Factory as Faker;
use App\Models\Score;
use App\Repositories\ScoreRepository;

trait MakeScoreTrait
{
    /**
     * Create fake instance of Score and save it in database
     *
     * @param array $scoreFields
     * @return Score
     */
    public function makeScore($scoreFields = [])
    {
        /** @var ScoreRepository $scoreRepo */
        $scoreRepo = App::make(ScoreRepository::class);
        $theme = $this->fakeScoreData($scoreFields);
        return $scoreRepo->create($theme);
    }

    /**
     * Get fake instance of Score
     *
     * @param array $scoreFields
     * @return Score
     */
    public function fakeScore($scoreFields = [])
    {
        return new Score($this->fakeScoreData($scoreFields));
    }

    /**
     * Get fake data of Score
     *
     * @param array $postFields
     * @return array
     */
    public function fakeScoreData($scoreFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'score_type_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'scoreable_id' => $fake->randomDigitNotNull,
            'scoreable_type' => $fake->word,
            'status' => $fake->word,
            'score' => $fake->randomDigitNotNull,
            'comment' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s')
        ], $scoreFields);
    }
}
