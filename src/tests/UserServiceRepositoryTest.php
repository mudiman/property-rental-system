<?php

use App\Models\UserService;
use App\Repositories\UserServiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserServiceRepositoryTest extends TestCase
{
    use MakeUserServiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserServiceRepository
     */
    protected $userServiceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->userServiceRepo = App::make(UserServiceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateUserService()
    {
        $userService = $this->fakeUserServiceData();
        $createdUserService = $this->userServiceRepo->create($userService);
        $createdUserService = $createdUserService->toArray();
        $this->assertArrayHasKey('id', $createdUserService);
        $this->assertNotNull($createdUserService['id'], 'Created UserService must have id specified');
        $this->assertNotNull(UserService::find($createdUserService['id']), 'UserService with given id must be in DB');
        $this->assertModelData($userService, $createdUserService);
    }

    /**
     * @test read
     */
    public function testReadUserService()
    {
        $userService = $this->makeUserService();
        $dbUserService = $this->userServiceRepo->find($userService->id);
        $dbUserService = $dbUserService->toArray();
        $this->assertModelData($userService->toArray(), $dbUserService);
    }

    /**
     * @test update
     */
    public function testUpdateUserService()
    {
        $userService = $this->makeUserService();
        $fakeUserService = $this->fakeUserServiceData();
        $updatedUserService = $this->userServiceRepo->update($fakeUserService, $userService->id);
        $this->assertModelData($fakeUserService, $updatedUserService->toArray());
        $dbUserService = $this->userServiceRepo->find($userService->id);
        $this->assertModelData($fakeUserService, $dbUserService->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUserService()
    {
        $userService = $this->makeUserService();
        $resp = $this->userServiceRepo->delete($userService->id);
        $this->assertTrue($resp);
        $this->assertNull(UserService::find($userService->id), 'UserService should not exist in DB');
    }
}
