<?php

use App\Models\ScoreType;
use App\Repositories\ScoreTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTypeRepositoryTest extends TestCase
{
    use MakeScoreTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ScoreTypeRepository
     */
    protected $scoreTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->scoreTypeRepo = App::make(ScoreTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateScoreType()
    {
        $scoreType = $this->fakeScoreTypeData();
        $createdScoreType = $this->scoreTypeRepo->create($scoreType);
        $createdScoreType = $createdScoreType->toArray();
        $this->assertArrayHasKey('id', $createdScoreType);
        $this->assertNotNull($createdScoreType['id'], 'Created ScoreType must have id specified');
        $this->assertNotNull(ScoreType::find($createdScoreType['id']), 'ScoreType with given id must be in DB');
        $this->assertModelData($scoreType, $createdScoreType);
    }

    /**
     * @test read
     */
    public function testReadScoreType()
    {
        $scoreType = $this->makeScoreType();
        $dbScoreType = $this->scoreTypeRepo->find($scoreType->id);
        $dbScoreType = $dbScoreType->toArray();
        $this->assertModelData($scoreType->toArray(), $dbScoreType);
    }

    /**
     * @test update
     */
    public function testUpdateScoreType()
    {
        $scoreType = $this->makeScoreType();
        $fakeScoreType = $this->fakeScoreTypeData();
        $updatedScoreType = $this->scoreTypeRepo->update($fakeScoreType, $scoreType->id);
        $this->assertModelData($fakeScoreType, $updatedScoreType->toArray());
        $dbScoreType = $this->scoreTypeRepo->find($scoreType->id);
        $this->assertModelData($fakeScoreType, $dbScoreType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteScoreType()
    {
        $scoreType = $this->makeScoreType();
        $resp = $this->scoreTypeRepo->delete($scoreType->id);
        $this->assertTrue($resp);
        $this->assertNull(ScoreType::find($scoreType->id), 'ScoreType should not exist in DB');
    }
}
