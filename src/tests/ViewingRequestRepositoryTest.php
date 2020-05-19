<?php

use App\Models\ViewingRequest;
use App\Repositories\ViewingRequestRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewingRequestRepositoryTest extends TestCase
{
    use MakeViewingRequestTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ViewingRequestRepository
     */
    protected $viewingRequestRepo;

    public function setUp()
    {
        parent::setUp();
        $this->viewingRequestRepo = App::make(ViewingRequestRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateViewingRequest()
    {
        $viewingRequest = $this->fakeViewingRequestData();
        $createdViewingRequest = $this->viewingRequestRepo->create($viewingRequest);
        $createdViewingRequest = $createdViewingRequest->toArray();
        $this->assertArrayHasKey('id', $createdViewingRequest);
        $this->assertNotNull($createdViewingRequest['id'], 'Created ViewingRequest must have id specified');
        $this->assertNotNull(ViewingRequest::find($createdViewingRequest['id']), 'ViewingRequest with given id must be in DB');
        $this->assertModelData($viewingRequest, $createdViewingRequest);
    }

    /**
     * @test read
     */
    public function testReadViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $dbViewingRequest = $this->viewingRequestRepo->find($viewingRequest->id);
        $dbViewingRequest = $dbViewingRequest->toArray();
        $this->assertModelData($viewingRequest->toArray(), $dbViewingRequest);
    }

    /**
     * @test update
     */
    public function testUpdateViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $fakeViewingRequest = $this->fakeViewingRequestData();
        $updatedViewingRequest = $this->viewingRequestRepo->update($fakeViewingRequest, $viewingRequest->id);
        $this->assertModelData($fakeViewingRequest, $updatedViewingRequest->toArray());
        $dbViewingRequest = $this->viewingRequestRepo->find($viewingRequest->id);
        $this->assertModelData($fakeViewingRequest, $dbViewingRequest->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteViewingRequest()
    {
        $viewingRequest = $this->makeViewingRequest();
        $resp = $this->viewingRequestRepo->delete($viewingRequest->id);
        $this->assertTrue($resp);
        $this->assertNull(ViewingRequest::find($viewingRequest->id), 'ViewingRequest should not exist in DB');
    }
}
