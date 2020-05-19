<?php

use Faker\Factory as Faker;
use App\Models\Services;
use App\Repositories\ServicesRepository;

trait MakeServicesTrait
{
    /**
     * Create fake instance of Services and save it in database
     *
     * @param array $servicesFields
     * @return Services
     */
    public function makeServices($servicesFields = [])
    {
        /** @var ServicesRepository $servicesRepo */
        $servicesRepo = App::make(ServicesRepository::class);
        $theme = $this->fakeServicesData($servicesFields);
        return $servicesRepo->create($theme);
    }

    /**
     * Get fake instance of Services
     *
     * @param array $servicesFields
     * @return Services
     */
    public function fakeServices($servicesFields = [])
    {
        return new Services($this->fakeServicesData($servicesFields));
    }

    /**
     * Get fake data of Services
     *
     * @param array $postFields
     * @return array
     */
    public function fakeServicesData($servicesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'created_by' => $fake->randomDigitNotNull,
            'title' => $fake->word,
            'description' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $servicesFields);
    }
}
