<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeApiTest extends TestCase
{
    use MakeLikeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLike()
    {
        $like = $this->fakeLikeData();
        $this->json('POST', '/api/v1/likes', $like);

        $this->assertApiResponse($like);
    }

    /**
     * @test
     */
    public function testReadLike()
    {
        $like = $this->makeLike();
        $this->json('GET', '/api/v1/likes/'.$like->id);

        $this->assertApiResponse($like->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLike()
    {
        $like = $this->makeLike();
        $editedLike = $this->fakeLikeData();

        $this->json('PUT', '/api/v1/likes/'.$like->id, $editedLike);

        $this->assertApiResponse($editedLike);
    }

    /**
     * @test
     */
    public function testDeleteLike()
    {
        $like = $this->makeLike();
        $this->json('DELETE', '/api/v1/likes/'.$like->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/likes/'.$like->id);

        $this->assertResponseStatus(404);
    }
}
