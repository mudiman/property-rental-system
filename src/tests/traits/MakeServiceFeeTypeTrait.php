<?php

use Faker\Factory as Faker;
use App\Models\ServiceFeeType;
use App\Repositories\ServiceFeeTypeRepository;

trait MakeServiceFeeTypeTrait
{
    /**
     * Create fake instance of ServiceFeeType and save it in database
     *
     * @param array $serviceFeeTypeFields
     * @return ServiceFeeType
     */
    public function makeServiceFeeType($serviceFeeTypeFields = [])
    {
        /** @var ServiceFeeTypeRepository $serviceFeeTypeRepo */
        $serviceFeeTypeRepo = App::make(ServiceFeeTypeRepository::class);
        $theme = $this->fakeServiceFeeTypeData($serviceFeeTypeFields);
        return $serviceFeeTypeRepo->create($theme);
    }

    /**
     * Get fake instance of ServiceFeeType
     *
     * @param array $serviceFeeTypeFields
     * @return ServiceFeeType
     */
    public function fakeServiceFeeType($serviceFeeTypeFields = [])
    {
        return new ServiceFeeType($this->fakeServiceFeeTypeData($serviceFeeTypeFields));
    }

    /**
     * Get fake data of ServiceFeeType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeServiceFeeTypeData($serviceFeeTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $serviceFeeTypeFields);
    }
}
