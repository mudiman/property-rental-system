<?php

use App\Models\PropertyRoomType;
use App\Repositories\PropertyRoomTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyRoomTypeRepositoryTest extends TestCase
{
    use MakePropertyRoomTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PropertyRoomTypeRepository
     */
    protected $propertyRoomTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->propertyRoomTypeRepo = App::make(PropertyRoomTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePropertyRoomType()
    {
        $propertyRoomType = $this->fakePropertyRoomTypeData();
        $createdPropertyRoomType = $this->propertyRoomTypeRepo->create($propertyRoomType);
        $createdPropertyRoomType = $createdPropertyRoomType->toArray();
        $this->assertArrayHasKey('id', $createdPropertyRoomType);
        $this->assertNotNull($createdPropertyRoomType['id'], 'Created PropertyRoomType must have id specified');
        $this->assertNotNull(PropertyRoomType::find($createdPropertyRoomType['id']), 'PropertyRoomType with given id must be in DB');
        $this->assertModelData($propertyRoomType, $createdPropertyRoomType);
    }

    /**
     * @test read
     */
    public function testReadPropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $dbPropertyRoomType = $this->propertyRoomTypeRepo->find($propertyRoomType->id);
        $dbPropertyRoomType = $dbPropertyRoomType->toArray();
        $this->assertModelData($propertyRoomType->toArray(), $dbPropertyRoomType);
    }

    /**
     * @test update
     */
    public function testUpdatePropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $fakePropertyRoomType = $this->fakePropertyRoomTypeData();
        $updatedPropertyRoomType = $this->propertyRoomTypeRepo->update($fakePropertyRoomType, $propertyRoomType->id);
        $this->assertModelData($fakePropertyRoomType, $updatedPropertyRoomType->toArray());
        $dbPropertyRoomType = $this->propertyRoomTypeRepo->find($propertyRoomType->id);
        $this->assertModelData($fakePropertyRoomType, $dbPropertyRoomType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePropertyRoomType()
    {
        $propertyRoomType = $this->makePropertyRoomType();
        $resp = $this->propertyRoomTypeRepo->delete($propertyRoomType->id);
        $this->assertTrue($resp);
        $this->assertNull(PropertyRoomType::find($propertyRoomType->id), 'PropertyRoomType should not exist in DB');
    }
}
