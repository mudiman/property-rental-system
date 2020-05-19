<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomTypeApiTest extends TestCase
{
    use MakeRoomTypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateRoomType()
    {
        $roomType = $this->fakeRoomTypeData();
        $this->json('POST', '/api/v1/roomTypes', $roomType);

        $this->assertApiResponse($roomType);
    }

    /**
     * @test
     */
    public function testReadRoomType()
    {
        $roomType = $this->makeRoomType();
        $this->json('GET', '/api/v1/roomTypes/'.$roomType->id);

        $this->assertApiResponse($roomType->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRoomType()
    {
        $roomType = $this->makeRoomType();
        $editedRoomType = $this->fakeRoomTypeData();

        $this->json('PUT', '/api/v1/roomTypes/'.$roomType->id, $editedRoomType);

        $this->assertApiResponse($editedRoomType);
    }

    /**
     * @test
     */
    public function testDeleteRoomType()
    {
        $roomType = $this->makeRoomType();
        $this->json('DELETE', '/api/v1/roomTypes/'.$roomType->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/roomTypes/'.$roomType->id);

        $this->assertResponseStatus(404);
    }
}
