<?php

use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentRepositoryTest extends TestCase
{
    use MakeDocumentTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DocumentRepository
     */
    protected $documentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->documentRepo = App::make(DocumentRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDocument()
    {
        $document = $this->fakeDocumentData();
        $createdDocument = $this->documentRepo->create($document);
        $createdDocument = $createdDocument->toArray();
        $this->assertArrayHasKey('id', $createdDocument);
        $this->assertNotNull($createdDocument['id'], 'Created Document must have id specified');
        $this->assertNotNull(Document::find($createdDocument['id']), 'Document with given id must be in DB');
        $this->assertModelData($document, $createdDocument);
    }

    /**
     * @test read
     */
    public function testReadDocument()
    {
        $document = $this->makeDocument();
        $dbDocument = $this->documentRepo->find($document->id);
        $dbDocument = $dbDocument->toArray();
        $this->assertModelData($document->toArray(), $dbDocument);
    }

    /**
     * @test update
     */
    public function testUpdateDocument()
    {
        $document = $this->makeDocument();
        $fakeDocument = $this->fakeDocumentData();
        $updatedDocument = $this->documentRepo->update($fakeDocument, $document->id);
        $this->assertModelData($fakeDocument, $updatedDocument->toArray());
        $dbDocument = $this->documentRepo->find($document->id);
        $this->assertModelData($fakeDocument, $dbDocument->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDocument()
    {
        $document = $this->makeDocument();
        $resp = $this->documentRepo->delete($document->id);
        $this->assertTrue($resp);
        $this->assertNull(Document::find($document->id), 'Document should not exist in DB');
    }
}
