<?php

use Faker\Factory as Faker;
use App\Models\Statistic;
use App\Repositories\StatisticRepository;

trait MakeStatisticTrait
{
    /**
     * Create fake instance of Statistic and save it in database
     *
     * @param array $statisticFields
     * @return Statistic
     */
    public function makeStatistic($statisticFields = [])
    {
        /** @var StatisticRepository $statisticRepo */
        $statisticRepo = App::make(StatisticRepository::class);
        $theme = $this->fakeStatisticData($statisticFields);
        return $statisticRepo->create($theme);
    }

    /**
     * Get fake instance of Statistic
     *
     * @param array $statisticFields
     * @return Statistic
     */
    public function fakeStatistic($statisticFields = [])
    {
        return new Statistic($this->fakeStatisticData($statisticFields));
    }

    /**
     * Get fake data of Statistic
     *
     * @param array $postFields
     * @return array
     */
    public function fakeStatisticData($statisticFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'property_id' => $fake->randomDigitNotNull,
            'listing_view' => $fake->randomDigitNotNull,
            'detail_view' => $fake->randomDigitNotNull,
            'viewed_datetime' => $fake->date('Y-m-d H:i:s'),
            'viewed_by_user' => $fake->randomDigitNotNull,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $statisticFields);
    }
}
