<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentApiTest extends TestCase
{
    use MakeDocumentTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDocument()
    {
        $document = $this->fakeDocumentData();
        $this->json('POST', '/api/v1/documents', $document);

        $this->assertApiResponse($document);
    }

    /**
     * @test
     */
    public function testReadDocument()
    {
        $document = $this->makeDocument();
        $this->json('GET', '/api/v1/documents/'.$document->id);

        $this->assertApiResponse($document->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDocument()
    {
        $document = $this->makeDocument();
        $editedDocument = $this->fakeDocumentData();

        $this->json('PUT', '/api/v1/documents/'.$document->id, $editedDocument);

        $this->assertApiResponse($editedDocument);
    }

    /**
     * @test
     */
    public function testDeleteDocument()
    {
        $document = $this->makeDocument();
        $this->json('DELETE', '/api/v1/documents/'.$document->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/documents/'.$document->id);

        $this->assertResponseStatus(404);
    }
}
