<?php

use Faker\Factory as Faker;
use App\Models\Extra;
use App\Repositories\ExtraRepository;

trait MakeExtraTrait
{
    /**
     * Create fake instance of Extra and save it in database
     *
     * @param array $extraFields
     * @return Extra
     */
    public function makeExtra($extraFields = [])
    {
        /** @var ExtraRepository $extraRepo */
        $extraRepo = App::make(ExtraRepository::class);
        $theme = $this->fakeExtraData($extraFields);
        return $extraRepo->create($theme);
    }

    /**
     * Get fake instance of Extra
     *
     * @param array $extraFields
     * @return Extra
     */
    public function fakeExtra($extraFields = [])
    {
        return new Extra($this->fakeExtraData($extraFields));
    }

    /**
     * Get fake data of Extra
     *
     * @param array $postFields
     * @return array
     */
    public function fakeExtraData($extraFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $extraFields);
    }
}
