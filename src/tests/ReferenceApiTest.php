<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReferenceApiTest extends TestCase
{
    use MakeReferenceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateReference()
    {
        $reference = $this->fakeReferenceData();
        $this->json('POST', '/api/v1/references', $reference);

        $this->assertApiResponse($reference);
    }

    /**
     * @test
     */
    public function testReadReference()
    {
        $reference = $this->makeReference();
        $this->json('GET', '/api/v1/references/'.$reference->id);

        $this->assertApiResponse($reference->toArray());
    }

    /**
     * @test
     */
    public function testUpdateReference()
    {
        $reference = $this->makeReference();
        $editedReference = $this->fakeReferenceData();

        $this->json('PUT', '/api/v1/references/'.$reference->id, $editedReference);

        $this->assertApiResponse($editedReference);
    }

    /**
     * @test
     */
    public function testDeleteReference()
    {
        $reference = $this->makeReference();
        $this->json('DELETE', '/api/v1/references/'.$reference->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/references/'.$reference->id);

        $this->assertResponseStatus(404);
    }
}
