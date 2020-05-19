<?php

use Faker\Factory as Faker;
use App\Models\Review;
use App\Repositories\ReviewRepository;

trait MakeReviewTrait
{
    /**
     * Create fake instance of Review and save it in database
     *
     * @param array $reviewFields
     * @return Review
     */
    public function makeReview($reviewFields = [])
    {
        /** @var ReviewRepository $reviewRepo */
        $reviewRepo = App::make(ReviewRepository::class);
        $theme = $this->fakeReviewData($reviewFields);
        return $reviewRepo->create($theme);
    }

    /**
     * Get fake instance of Review
     *
     * @param array $reviewFields
     * @return Review
     */
    public function fakeReview($reviewFields = [])
    {
        return new Review($this->fakeReviewData($reviewFields));
    }

    /**
     * Get fake data of Review
     *
     * @param array $postFields
     * @return array
     */
    public function fakeReviewData($reviewFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'by_user' => $fake->randomDigitNotNull,
            'for_user' => $fake->randomDigitNotNull,
            'comment' => $fake->word,
            'rating' => $fake->randomDigitNotNull,
            'punctuality' => $fake->randomDigitNotNull,
            'quality' => $fake->randomDigitNotNull,
            'reviewable_id' => $fake->randomDigitNotNull,
            'reviewable_type' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s')
        ], $reviewFields);
    }
}
