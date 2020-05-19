<?php

use Faker\Factory as Faker;
use App\Models\History;
use App\Repositories\HistoryRepository;

trait MakeHistoryTrait
{
    /**
     * Create fake instance of History and save it in database
     *
     * @param array $historyFields
     * @return History
     */
    public function makeHistory($historyFields = [])
    {
        /** @var HistoryRepository $historyRepo */
        $historyRepo = App::make(HistoryRepository::class);
        $theme = $this->fakeHistoryData($historyFields);
        return $historyRepo->create($theme);
    }

    /**
     * Get fake instance of History
     *
     * @param array $historyFields
     * @return History
     */
    public function fakeHistory($historyFields = [])
    {
        return new History($this->fakeHistoryData($historyFields));
    }

    /**
     * Get fake data of History
     *
     * @param array $postFields
     * @return array
     */
    public function fakeHistoryData($historyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'historiable_id' => $fake->randomDigitNotNull,
            'historiable_type' => $fake->word,
            'snapshot' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $historyFields);
    }
}
