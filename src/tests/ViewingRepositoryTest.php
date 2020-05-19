<?php

use App\Models\Viewing;
use App\Repositories\ViewingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewingRepositoryTest extends TestCase
{
    use MakeViewingTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ViewingRepository
     */
    protected $viewingRepo;

    public function setUp()
    {
        parent::setUp();
        $this->viewingRepo = App::make(ViewingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateViewing()
    {
        $viewing = $this->fakeViewingData();
        $createdViewing = $this->viewingRepo->create($viewing);
        $createdViewing = $createdViewing->toArray();
        $this->assertArrayHasKey('id', $createdViewing);
        $this->assertNotNull($createdViewing['id'], 'Created Viewing must have id specified');
        $this->assertNotNull(Viewing::find($createdViewing['id']), 'Viewing with given id must be in DB');
        $this->assertModelData($viewing, $createdViewing);
    }

    /**
     * @test read
     */
    public function testReadViewing()
    {
        $viewing = $this->makeViewing();
        $dbViewing = $this->viewingRepo->find($viewing->id);
        $dbViewing = $dbViewing->toArray();
        $this->assertModelData($viewing->toArray(), $dbViewing);
    }

    /**
     * @test update
     */
    public function testUpdateViewing()
    {
        $viewing = $this->makeViewing();
        $fakeViewing = $this->fakeViewingData();
        $updatedViewing = $this->viewingRepo->update($fakeViewing, $viewing->id);
        $this->assertModelData($fakeViewing, $updatedViewing->toArray());
        $dbViewing = $this->viewingRepo->find($viewing->id);
        $this->assertModelData($fakeViewing, $dbViewing->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteViewing()
    {
        $viewing = $this->makeViewing();
        $resp = $this->viewingRepo->delete($viewing->id);
        $this->assertTrue($resp);
        $this->assertNull(Viewing::find($viewing->id), 'Viewing should not exist in DB');
    }
}
