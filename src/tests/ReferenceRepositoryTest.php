<?php

use App\Models\Reference;
use App\Repositories\ReferenceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReferenceRepositoryTest extends TestCase
{
    use MakeReferenceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ReferenceRepository
     */
    protected $referenceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->referenceRepo = App::make(ReferenceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateReference()
    {
        $reference = $this->fakeReferenceData();
        $createdReference = $this->referenceRepo->create($reference);
        $createdReference = $createdReference->toArray();
        $this->assertArrayHasKey('id', $createdReference);
        $this->assertNotNull($createdReference['id'], 'Created Reference must have id specified');
        $this->assertNotNull(Reference::find($createdReference['id']), 'Reference with given id must be in DB');
        $this->assertModelData($reference, $createdReference);
    }

    /**
     * @test read
     */
    public function testReadReference()
    {
        $reference = $this->makeReference();
        $dbReference = $this->referenceRepo->find($reference->id);
        $dbReference = $dbReference->toArray();
        $this->assertModelData($reference->toArray(), $dbReference);
    }

    /**
     * @test update
     */
    public function testUpdateReference()
    {
        $reference = $this->makeReference();
        $fakeReference = $this->fakeReferenceData();
        $updatedReference = $this->referenceRepo->update($fakeReference, $reference->id);
        $this->assertModelData($fakeReference, $updatedReference->toArray());
        $dbReference = $this->referenceRepo->find($reference->id);
        $this->assertModelData($fakeReference, $dbReference->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteReference()
    {
        $reference = $this->makeReference();
        $resp = $this->referenceRepo->delete($reference->id);
        $this->assertTrue($resp);
        $this->assertNull(Reference::find($reference->id), 'Reference should not exist in DB');
    }
}
