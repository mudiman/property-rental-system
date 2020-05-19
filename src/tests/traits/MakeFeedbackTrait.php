<?php

use Faker\Factory as Faker;
use App\Models\Feedback;
use App\Repositories\FeedbackRepository;

trait MakeFeedbackTrait
{
    /**
     * Create fake instance of Feedback and save it in database
     *
     * @param array $feedbackFields
     * @return Feedback
     */
    public function makeFeedback($feedbackFields = [])
    {
        /** @var FeedbackRepository $feedbackRepo */
        $feedbackRepo = App::make(FeedbackRepository::class);
        $theme = $this->fakeFeedbackData($feedbackFields);
        return $feedbackRepo->create($theme);
    }

    /**
     * Get fake instance of Feedback
     *
     * @param array $feedbackFields
     * @return Feedback
     */
    public function fakeFeedback($feedbackFields = [])
    {
        return new Feedback($this->fakeFeedbackData($feedbackFields));
    }

    /**
     * Get fake data of Feedback
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFeedbackData($feedbackFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'title' => $fake->word,
            'description' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $feedbackFields);
    }
}
