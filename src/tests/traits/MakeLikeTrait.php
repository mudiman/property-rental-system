<?php

use Faker\Factory as Faker;
use App\Models\Like;
use App\Repositories\LikeRepository;

trait MakeLikeTrait
{
    /**
     * Create fake instance of Like and save it in database
     *
     * @param array $likeFields
     * @return Like
     */
    public function makeLike($likeFields = [])
    {
        /** @var LikeRepository $likeRepo */
        $likeRepo = App::make(LikeRepository::class);
        $theme = $this->fakeLikeData($likeFields);
        return $likeRepo->create($theme);
    }

    /**
     * Get fake instance of Like
     *
     * @param array $likeFields
     * @return Like
     */
    public function fakeLike($likeFields = [])
    {
        return new Like($this->fakeLikeData($likeFields));
    }

    /**
     * Get fake data of Like
     *
     * @param array $postFields
     * @return array
     */
    public function fakeLikeData($likeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'likeable_id' => $fake->randomDigitNotNull,
            'likeable_type' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'type' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $likeFields);
    }
}
