<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageApiTest extends TestCase
{
    use MakeImageTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateImage()
    {
        $image = $this->fakeImageData();
        $this->json('POST', '/api/v1/images', $image);

        $this->assertApiResponse($image);
    }

    /**
     * @test
     */
    public function testReadImage()
    {
        $image = $this->makeImage();
        $this->json('GET', '/api/v1/images/'.$image->id);

        $this->assertApiResponse($image->toArray());
    }

    /**
     * @test
     */
    public function testUpdateImage()
    {
        $image = $this->makeImage();
        $editedImage = $this->fakeImageData();

        $this->json('PUT', '/api/v1/images/'.$image->id, $editedImage);

        $this->assertApiResponse($editedImage);
    }

    /**
     * @test
     */
    public function testDeleteImage()
    {
        $image = $this->makeImage();
        $this->json('DELETE', '/api/v1/images/'.$image->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/images/'.$image->id);

        $this->assertResponseStatus(404);
    }
}
