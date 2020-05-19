<?php

use App\Models\Like;
use App\Repositories\LikeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeRepositoryTest extends TestCase
{
    use MakeLikeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LikeRepository
     */
    protected $likeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->likeRepo = App::make(LikeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLike()
    {
        $like = $this->fakeLikeData();
        $createdLike = $this->likeRepo->create($like);
        $createdLike = $createdLike->toArray();
        $this->assertArrayHasKey('id', $createdLike);
        $this->assertNotNull($createdLike['id'], 'Created Like must have id specified');
        $this->assertNotNull(Like::find($createdLike['id']), 'Like with given id must be in DB');
        $this->assertModelData($like, $createdLike);
    }

    /**
     * @test read
     */
    public function testReadLike()
    {
        $like = $this->makeLike();
        $dbLike = $this->likeRepo->find($like->id);
        $dbLike = $dbLike->toArray();
        $this->assertModelData($like->toArray(), $dbLike);
    }

    /**
     * @test update
     */
    public function testUpdateLike()
    {
        $like = $this->makeLike();
        $fakeLike = $this->fakeLikeData();
        $updatedLike = $this->likeRepo->update($fakeLike, $like->id);
        $this->assertModelData($fakeLike, $updatedLike->toArray());
        $dbLike = $this->likeRepo->find($like->id);
        $this->assertModelData($fakeLike, $dbLike->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLike()
    {
        $like = $this->makeLike();
        $resp = $this->likeRepo->delete($like->id);
        $this->assertTrue($resp);
        $this->assertNull(Like::find($like->id), 'Like should not exist in DB');
    }
}
