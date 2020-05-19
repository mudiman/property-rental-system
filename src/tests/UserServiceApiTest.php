<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserServiceApiTest extends TestCase
{
    use MakeUserServiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateUserService()
    {
        $userService = $this->fakeUserServiceData();
        $this->json('POST', '/api/v1/userServices', $userService);

        $this->assertApiResponse($userService);
    }

    /**
     * @test
     */
    public function testReadUserService()
    {
        $userService = $this->makeUserService();
        $this->json('GET', '/api/v1/userServices/'.$userService->id);

        $this->assertApiResponse($userService->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUserService()
    {
        $userService = $this->makeUserService();
        $editedUserService = $this->fakeUserServiceData();

        $this->json('PUT', '/api/v1/userServices/'.$userService->id, $editedUserService);

        $this->assertApiResponse($editedUserService);
    }

    /**
     * @test
     */
    public function testDeleteUserService()
    {
        $userService = $this->makeUserService();
        $this->json('DELETE', '/api/v1/userServices/'.$userService->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/userServices/'.$userService->id);

        $this->assertResponseStatus(404);
    }
}
