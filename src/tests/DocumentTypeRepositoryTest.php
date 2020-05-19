<?php

use App\Models\DocumentType;
use App\Repositories\DocumentTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentTypeRepositoryTest extends TestCase
{
    use MakeDocumentTypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DocumentTypeRepository
     */
    protected $documentTypeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->documentTypeRepo = App::make(DocumentTypeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDocumentType()
    {
        $documentType = $this->fakeDocumentTypeData();
        $createdDocumentType = $this->documentTypeRepo->create($documentType);
        $createdDocumentType = $createdDocumentType->toArray();
        $this->assertArrayHasKey('id', $createdDocumentType);
        $this->assertNotNull($createdDocumentType['id'], 'Created DocumentType must have id specified');
        $this->assertNotNull(DocumentType::find($createdDocumentType['id']), 'DocumentType with given id must be in DB');
        $this->assertModelData($documentType, $createdDocumentType);
    }

    /**
     * @test read
     */
    public function testReadDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $dbDocumentType = $this->documentTypeRepo->find($documentType->id);
        $dbDocumentType = $dbDocumentType->toArray();
        $this->assertModelData($documentType->toArray(), $dbDocumentType);
    }

    /**
     * @test update
     */
    public function testUpdateDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $fakeDocumentType = $this->fakeDocumentTypeData();
        $updatedDocumentType = $this->documentTypeRepo->update($fakeDocumentType, $documentType->id);
        $this->assertModelData($fakeDocumentType, $updatedDocumentType->toArray());
        $dbDocumentType = $this->documentTypeRepo->find($documentType->id);
        $this->assertModelData($fakeDocumentType, $dbDocumentType->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $resp = $this->documentTypeRepo->delete($documentType->id);
        $this->assertTrue($resp);
        $this->assertNull(DocumentType::find($documentType->id), 'DocumentType should not exist in DB');
    }
}
