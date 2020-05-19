<?php

use Faker\Factory as Faker;
use App\Models\ServiceType;
use App\Repositories\ServiceTypeRepository;

trait MakeServiceTypeTrait
{
    /**
     * Create fake instance of ServiceType and save it in database
     *
     * @param array $serviceTypeFields
     * @return ServiceType
     */
    public function makeServiceType($serviceTypeFields = [])
    {
        /** @var ServiceTypeRepository $serviceTypeRepo */
        $serviceTypeRepo = App::make(ServiceTypeRepository::class);
        $theme = $this->fakeServiceTypeData($serviceTypeFields);
        return $serviceTypeRepo->create($theme);
    }

    /**
     * Get fake instance of ServiceType
     *
     * @param array $serviceTypeFields
     * @return ServiceType
     */
    public function fakeServiceType($serviceTypeFields = [])
    {
        return new ServiceType($this->fakeServiceTypeData($serviceTypeFields));
    }

    /**
     * Get fake data of ServiceType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeServiceTypeData($serviceTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $serviceTypeFields);
    }
}
