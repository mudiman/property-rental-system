<?php

use App\Models\BathroomType;
use App\Repositories\BathroomTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BathroomTypeRepositoryTest extends TestCase
{
    use MakeBathroomTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var BathroomTypeRepository
     */
    protected $bathroomTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->bathroomTypeRepo = App::make(BathroomTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBathroomType()
    {
        $bathroomType = $this->fakeBathroomTypeData();
        $createdBathroomType = $this->bathroomTypeRepo->create($bathroomType);
        $createdBathroomType = $createdBathroomType->toArray();
        $this->assertArrayHasKey('id', $createdBathroomType);
        $this->assertNotNull($createdBathroomType['id'], 'Created BathroomType must have id specified');
        $this->assertNotNull(BathroomType::find($createdBathroomType['id']), 'BathroomType with given id must be in DB');
        $this->assertModelData($bathroomType, $createdBathroomType);
    }

    /**
     * @test read
     */
    public function testReadBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $dbBathroomType = $this->bathroomTypeRepo->find($bathroomType->id);
        $dbBathroomType = $dbBathroomType->toArray();
        $this->assertModelData($bathroomType->toArray(), $dbBathroomType);
    }

    /**
     * @test update
     */
    public function testUpdateBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $fakeBathroomType = $this->fakeBathroomTypeData();
        $updatedBathroomType = $this->bathroomTypeRepo->update($fakeBathroomType, $bathroomType->id);
        $this->assertModelData($fakeBathroomType, $updatedBathroomType->toArray());
        $dbBathroomType = $this->bathroomTypeRepo->find($bathroomType->id);
        $this->assertModelData($fakeBathroomType, $dbBathroomType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBathroomType()
    {
        $bathroomType = $this->makeBathroomType();
        $resp = $this->bathroomTypeRepo->delete($bathroomType->id);
        $this->assertTrue($resp);
        $this->assertNull(BathroomType::find($bathroomType->id), 'BathroomType should not exist in DB');
    }
}
