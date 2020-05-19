<?php

use Faker\Factory as Faker;
use App\Models\Agency;
use App\Repositories\AgencyRepository;

trait MakeAgencyTrait
{
    /**
     * Create fake instance of Agency and save it in database
     *
     * @param array $agencyFields
     * @return Agency
     */
    public function makeAgency($agencyFields = [])
    {
        /** @var AgencyRepository $agencyRepo */
        $agencyRepo = App::make(AgencyRepository::class);
        $theme = $this->fakeAgencyData($agencyFields);
        return $agencyRepo->create($theme);
    }

    /**
     * Get fake instance of Agency
     *
     * @param array $agencyFields
     * @return Agency
     */
    public function fakeAgency($agencyFields = [])
    {
        return new Agency($this->fakeAgencyData($agencyFields));
    }

    /**
     * Get fake data of Agency
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAgencyData($agencyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'payin_id' => $fake->randomDigitNotNull,
            'name' => $fake->word,
            'status' => $fake->word,
            'commission' => $fake->randomDigitNotNull,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $agencyFields);
    }
}
