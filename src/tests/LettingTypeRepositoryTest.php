<?php

use App\Models\LettingType;
use App\Repositories\LettingTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LettingTypeRepositoryTest extends TestCase
{
    use MakeLettingTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LettingTypeRepository
     */
    protected $lettingTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->lettingTypeRepo = App::make(LettingTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLettingType()
    {
        $lettingType = $this->fakeLettingTypeData();
        $createdLettingType = $this->lettingTypeRepo->create($lettingType);
        $createdLettingType = $createdLettingType->toArray();
        $this->assertArrayHasKey('id', $createdLettingType);
        $this->assertNotNull($createdLettingType['id'], 'Created LettingType must have id specified');
        $this->assertNotNull(LettingType::find($createdLettingType['id']), 'LettingType with given id must be in DB');
        $this->assertModelData($lettingType, $createdLettingType);
    }

    /**
     * @test read
     */
    public function testReadLettingType()
    {
        $lettingType = $this->makeLettingType();
        $dbLettingType = $this->lettingTypeRepo->find($lettingType->id);
        $dbLettingType = $dbLettingType->toArray();
        $this->assertModelData($lettingType->toArray(), $dbLettingType);
    }

    /**
     * @test update
     */
    public function testUpdateLettingType()
    {
        $lettingType = $this->makeLettingType();
        $fakeLettingType = $this->fakeLettingTypeData();
        $updatedLettingType = $this->lettingTypeRepo->update($fakeLettingType, $lettingType->id);
        $this->assertModelData($fakeLettingType, $updatedLettingType->toArray());
        $dbLettingType = $this->lettingTypeRepo->find($lettingType->id);
        $this->assertModelData($fakeLettingType, $dbLettingType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLettingType()
    {
        $lettingType = $this->makeLettingType();
        $resp = $this->lettingTypeRepo->delete($lettingType->id);
        $this->assertTrue($resp);
        $this->assertNull(LettingType::find($lettingType->id), 'LettingType should not exist in DB');
    }
}
