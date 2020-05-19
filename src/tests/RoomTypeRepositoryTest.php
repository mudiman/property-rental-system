<?php

use App\Models\RoomType;
use App\Repositories\RoomTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomTypeRepositoryTest extends TestCase
{
    use MakeRoomTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RoomTypeRepository
     */
    protected $roomTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->roomTypeRepo = App::make(RoomTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateRoomType()
    {
        $roomType = $this->fakeRoomTypeData();
        $createdRoomType = $this->roomTypeRepo->create($roomType);
        $createdRoomType = $createdRoomType->toArray();
        $this->assertArrayHasKey('id', $createdRoomType);
        $this->assertNotNull($createdRoomType['id'], 'Created RoomType must have id specified');
        $this->assertNotNull(RoomType::find($createdRoomType['id']), 'RoomType with given id must be in DB');
        $this->assertModelData($roomType, $createdRoomType);
    }

    /**
     * @test read
     */
    public function testReadRoomType()
    {
        $roomType = $this->makeRoomType();
        $dbRoomType = $this->roomTypeRepo->find($roomType->id);
        $dbRoomType = $dbRoomType->toArray();
        $this->assertModelData($roomType->toArray(), $dbRoomType);
    }

    /**
     * @test update
     */
    public function testUpdateRoomType()
    {
        $roomType = $this->makeRoomType();
        $fakeRoomType = $this->fakeRoomTypeData();
        $updatedRoomType = $this->roomTypeRepo->update($fakeRoomType, $roomType->id);
        $this->assertModelData($fakeRoomType, $updatedRoomType->toArray());
        $dbRoomType = $this->roomTypeRepo->find($roomType->id);
        $this->assertModelData($fakeRoomType, $dbRoomType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRoomType()
    {
        $roomType = $this->makeRoomType();
        $resp = $this->roomTypeRepo->delete($roomType->id);
        $this->assertTrue($resp);
        $this->assertNull(RoomType::find($roomType->id), 'RoomType should not exist in DB');
    }
}
