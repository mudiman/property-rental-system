<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeviceApiTest extends TestCase
{
    use MakeDeviceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDevice()
    {
        $device = $this->fakeDeviceData();
        $this->json('POST', '/api/v1/devices', $device);

        $this->assertApiResponse($device);
    }

    /**
     * @test
     */
    public function testReadDevice()
    {
        $device = $this->makeDevice();
        $this->json('GET', '/api/v1/devices/'.$device->id);

        $this->assertApiResponse($device->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDevice()
    {
        $device = $this->makeDevice();
        $editedDevice = $this->fakeDeviceData();

        $this->json('PUT', '/api/v1/devices/'.$device->id, $editedDevice);

        $this->assertApiResponse($editedDevice);
    }

    /**
     * @test
     */
    public function testDeleteDevice()
    {
        $device = $this->makeDevice();
        $this->json('DELETE', '/api/v1/devices/'.$device->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/devices/'.$device->id);

        $this->assertResponseStatus(404);
    }
}
