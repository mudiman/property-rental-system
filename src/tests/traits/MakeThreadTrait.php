<?php

use Faker\Factory as Faker;
use App\Models\Thread;
use App\Repositories\ThreadRepository;

trait MakeThreadTrait
{
    /**
     * Create fake instance of Thread and save it in database
     *
     * @param array $threadFields
     * @return Thread
     */
    public function makeThread($threadFields = [])
    {
        /** @var ThreadRepository $threadRepo */
        $threadRepo = App::make(ThreadRepository::class);
        $theme = $this->fakeThreadData($threadFields);
        return $threadRepo->create($theme);
    }

    /**
     * Get fake instance of Thread
     *
     * @param array $threadFields
     * @return Thread
     */
    public function fakeThread($threadFields = [])
    {
        return new Thread($this->fakeThreadData($threadFields));
    }

    /**
     * Get fake data of Thread
     *
     * @param array $postFields
     * @return array
     */
    public function fakeThreadData($threadFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'status' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $threadFields);
    }
}
