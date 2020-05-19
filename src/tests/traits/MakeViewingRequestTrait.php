<?php

use Faker\Factory as Faker;
use App\Models\ViewingRequest;
use App\Repositories\ViewingRequestRepository;

trait MakeViewingRequestTrait
{
    /**
     * Create fake instance of ViewingRequest and save it in database
     *
     * @param array $viewingRequestFields
     * @return ViewingRequest
     */
    public function makeViewingRequest($viewingRequestFields = [])
    {
        /** @var ViewingRequestRepository $viewingRequestRepo */
        $viewingRequestRepo = App::make(ViewingRequestRepository::class);
        $theme = $this->fakeViewingRequestData($viewingRequestFields);
        return $viewingRequestRepo->create($theme);
    }

    /**
     * Get fake instance of ViewingRequest
     *
     * @param array $viewingRequestFields
     * @return ViewingRequest
     */
    public function fakeViewingRequest($viewingRequestFields = [])
    {
        return new ViewingRequest($this->fakeViewingRequestData($viewingRequestFields));
    }

    /**
     * Get fake data of ViewingRequest
     *
     * @param array $postFields
     * @return array
     */
    public function fakeViewingRequestData($viewingRequestFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'viewing_id' => $fake->word,
            'view_by_user' => $fake->word,
            'checkin' => $fake->word,
            'status' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $viewingRequestFields);
    }
}
