<?php

use Faker\Factory as Faker;
use App\Models\Device;
use App\Repositories\DeviceRepository;

trait MakeDeviceTrait
{
    /**
     * Create fake instance of Device and save it in database
     *
     * @param array $deviceFields
     * @return Device
     */
    public function makeDevice($deviceFields = [])
    {
        /** @var DeviceRepository $deviceRepo */
        $deviceRepo = App::make(DeviceRepository::class);
        $theme = $this->fakeDeviceData($deviceFields);
        return $deviceRepo->create($theme);
    }

    /**
     * Get fake instance of Device
     *
     * @param array $deviceFields
     * @return Device
     */
    public function fakeDevice($deviceFields = [])
    {
        return new Device($this->fakeDeviceData($deviceFields));
    }

    /**
     * Get fake data of Device
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDeviceData($deviceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'api_version' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'type' => $fake->word,
            'device_id' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s')
        ], $deviceFields);
    }
}
