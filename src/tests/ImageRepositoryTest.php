<?php

use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageRepositoryTest extends TestCase
{
    use MakeImageTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ImageRepository
     */
    protected $imageRepo;

    public function setUp()
    {
        parent::setUp();
        $this->imageRepo = App::make(ImageRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateImage()
    {
        $image = $this->fakeImageData();
        $createdImage = $this->imageRepo->create($image);
        $createdImage = $createdImage->toArray();
        $this->assertArrayHasKey('id', $createdImage);
        $this->assertNotNull($createdImage['id'], 'Created Image must have id specified');
        $this->assertNotNull(Image::find($createdImage['id']), 'Image with given id must be in DB');
        $this->assertModelData($image, $createdImage);
    }

    /**
     * @test read
     */
    public function testReadImage()
    {
        $image = $this->makeImage();
        $dbImage = $this->imageRepo->find($image->id);
        $dbImage = $dbImage->toArray();
        $this->assertModelData($image->toArray(), $dbImage);
    }

    /**
     * @test update
     */
    public function testUpdateImage()
    {
        $image = $this->makeImage();
        $fakeImage = $this->fakeImageData();
        $updatedImage = $this->imageRepo->update($fakeImage, $image->id);
        $this->assertModelData($fakeImage, $updatedImage->toArray());
        $dbImage = $this->imageRepo->find($image->id);
        $this->assertModelData($fakeImage, $dbImage->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteImage()
    {
        $image = $this->makeImage();
        $resp = $this->imageRepo->delete($image->id);
        $this->assertTrue($resp);
        $this->assertNull(Image::find($image->id), 'Image should not exist in DB');
    }
}
