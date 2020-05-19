<?php

use Faker\Factory as Faker;
use App\Models\Report;
use App\Repositories\ReportRepository;

trait MakeReportTrait
{
    /**
     * Create fake instance of Report and save it in database
     *
     * @param array $reportFields
     * @return Report
     */
    public function makeReport($reportFields = [])
    {
        /** @var ReportRepository $reportRepo */
        $reportRepo = App::make(ReportRepository::class);
        $theme = $this->fakeReportData($reportFields);
        return $reportRepo->create($theme);
    }

    /**
     * Get fake instance of Report
     *
     * @param array $reportFields
     * @return Report
     */
    public function fakeReport($reportFields = [])
    {
        return new Report($this->fakeReportData($reportFields));
    }

    /**
     * Get fake data of Report
     *
     * @param array $postFields
     * @return array
     */
    public function fakeReportData($reportFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'by_user' => $fake->randomDigitNotNull,
            'comment' => $fake->word,
            'reportable_id' => $fake->randomDigitNotNull,
            'reportable_type' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $reportFields);
    }
}
