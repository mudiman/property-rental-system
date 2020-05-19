<?php

use App\Models\Device;
use App\Repositories\DeviceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeviceRepositoryTest extends TestCase
{
    use MakeDeviceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DeviceRepository
     */
    protected $deviceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->deviceRepo = App::make(DeviceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDevice()
    {
        $device = $this->fakeDeviceData();
        $createdDevice = $this->deviceRepo->create($device);
        $createdDevice = $createdDevice->toArray();
        $this->assertArrayHasKey('id', $createdDevice);
        $this->assertNotNull($createdDevice['id'], 'Created Device must have id specified');
        $this->assertNotNull(Device::find($createdDevice['id']), 'Device with given id must be in DB');
        $this->assertModelData($device, $createdDevice);
    }

    /**
     * @test read
     */
    public function testReadDevice()
    {
        $device = $this->makeDevice();
        $dbDevice = $this->deviceRepo->find($device->id);
        $dbDevice = $dbDevice->toArray();
        $this->assertModelData($device->toArray(), $dbDevice);
    }

    /**
     * @test update
     */
    public function testUpdateDevice()
    {
        $device = $this->makeDevice();
        $fakeDevice = $this->fakeDeviceData();
        $updatedDevice = $this->deviceRepo->update($fakeDevice, $device->id);
        $this->assertModelData($fakeDevice, $updatedDevice->toArray());
        $dbDevice = $this->deviceRepo->find($device->id);
        $this->assertModelData($fakeDevice, $dbDevice->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDevice()
    {
        $device = $this->makeDevice();
        $resp = $this->deviceRepo->delete($device->id);
        $this->assertTrue($resp);
        $this->assertNull(Device::find($device->id), 'Device should not exist in DB');
    }
}
