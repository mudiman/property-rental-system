<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExtraApiTest extends TestCase
{
    use MakeExtraTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateExtra()
    {
        $extra = $this->fakeExtraData();
        $this->json('POST', '/api/v1/extras', $extra);

        $this->assertApiResponse($extra);
    }

    /**
     * @test
     */
    public function testReadExtra()
    {
        $extra = $this->makeExtra();
        $this->json('GET', '/api/v1/extras/'.$extra->id);

        $this->assertApiResponse($extra->toArray());
    }

    /**
     * @test
     */
    public function testUpdateExtra()
    {
        $extra = $this->makeExtra();
        $editedExtra = $this->fakeExtraData();

        $this->json('PUT', '/api/v1/extras/'.$extra->id, $editedExtra);

        $this->assertApiResponse($editedExtra);
    }

    /**
     * @test
     */
    public function testDeleteExtra()
    {
        $extra = $this->makeExtra();
        $this->json('DELETE', '/api/v1/extras/'.$extra->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/extras/'.$extra->id);

        $this->assertResponseStatus(404);
    }
}
