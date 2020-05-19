<?php

use Faker\Factory as Faker;
use App\Models\Viewing;
use App\Repositories\ViewingRepository;

trait MakeViewingTrait
{
    /**
     * Create fake instance of Viewing and save it in database
     *
     * @param array $viewingFields
     * @return Viewing
     */
    public function makeViewing($viewingFields = [])
    {
        /** @var ViewingRepository $viewingRepo */
        $viewingRepo = App::make(ViewingRepository::class);
        $theme = $this->fakeViewingData($viewingFields);
        return $viewingRepo->create($theme);
    }

    /**
     * Get fake instance of Viewing
     *
     * @param array $viewingFields
     * @return Viewing
     */
    public function fakeViewing($viewingFields = [])
    {
        return new Viewing($this->fakeViewingData($viewingFields));
    }

    /**
     * Get fake data of Viewing
     *
     * @param array $postFields
     * @return array
     */
    public function fakeViewingData($viewingFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'property_id' => $fake->word,
            'conducted_by' => $fake->word,
            'start_datetime' => $fake->date('Y-m-d H:i:s'),
            'end_datetime' => $fake->date('Y-m-d H:i:s'),
            'type' => $fake->word,
            'status' => $fake->word,
            'checkin' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $viewingFields);
    }
}
