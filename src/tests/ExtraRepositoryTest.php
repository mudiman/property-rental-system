<?php

use App\Models\Extra;
use App\Repositories\ExtraRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExtraRepositoryTest extends TestCase
{
    use MakeExtraTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ExtraRepository
     */
    protected $extraRepo;

    public function setUp()
    {
        parent::setUp();
        $this->extraRepo = App::make(ExtraRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateExtra()
    {
        $extra = $this->fakeExtraData();
        $createdExtra = $this->extraRepo->create($extra);
        $createdExtra = $createdExtra->toArray();
        $this->assertArrayHasKey('id', $createdExtra);
        $this->assertNotNull($createdExtra['id'], 'Created Extra must have id specified');
        $this->assertNotNull(Extra::find($createdExtra['id']), 'Extra with given id must be in DB');
        $this->assertModelData($extra, $createdExtra);
    }

    /**
     * @test read
     */
    public function testReadExtra()
    {
        $extra = $this->makeExtra();
        $dbExtra = $this->extraRepo->find($extra->id);
        $dbExtra = $dbExtra->toArray();
        $this->assertModelData($extra->toArray(), $dbExtra);
    }

    /**
     * @test update
     */
    public function testUpdateExtra()
    {
        $extra = $this->makeExtra();
        $fakeExtra = $this->fakeExtraData();
        $updatedExtra = $this->extraRepo->update($fakeExtra, $extra->id);
        $this->assertModelData($fakeExtra, $updatedExtra->toArray());
        $dbExtra = $this->extraRepo->find($extra->id);
        $this->assertModelData($fakeExtra, $dbExtra->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteExtra()
    {
        $extra = $this->makeExtra();
        $resp = $this->extraRepo->delete($extra->id);
        $this->assertTrue($resp);
        $this->assertNull(Extra::find($extra->id), 'Extra should not exist in DB');
    }
}
