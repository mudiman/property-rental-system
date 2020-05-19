<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentTypeApiTest extends TestCase
{
    use MakeDocumentTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDocumentType()
    {
        $documentType = $this->fakeDocumentTypeData();
        $this->json('POST', '/api/v1/documentTypes', $documentType);

        $this->assertApiResponse($documentType);
    }

    /**
     * @test
     */
    public function testReadDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $this->json('GET', '/api/v1/documentTypes/'.$documentType->id);

        $this->assertApiResponse($documentType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $editedDocumentType = $this->fakeDocumentTypeData();

        $this->json('PUT', '/api/v1/documentTypes/'.$documentType->id, $editedDocumentType);

        $this->assertApiResponse($editedDocumentType);
    }

    /**
     * @test
     */
    public function testDeleteDocumentType()
    {
        $documentType = $this->makeDocumentType();
        $this->json('DELETE', '/api/v1/documentTypes/'.$documentType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/documentTypes/'.$documentType->id);

        $this->assertResponseStatus(404);
    }
}
