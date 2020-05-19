<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatisticApiTest extends TestCase
{
    use MakeStatisticTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateStatistic()
    {
        $statistic = $this->fakeStatisticData();
        $this->json('POST', '/api/v1/statistics', $statistic);

        $this->assertApiResponse($statistic);
    }

    /**
     * @test
     */
    public function testReadStatistic()
    {
        $statistic = $this->makeStatistic();
        $this->json('GET', '/api/v1/statistics/'.$statistic->id);

        $this->assertApiResponse($statistic->toArray());
    }

    /**
     * @test
     */
    public function testUpdateStatistic()
    {
        $statistic = $this->makeStatistic();
        $editedStatistic = $this->fakeStatisticData();

        $this->json('PUT', '/api/v1/statistics/'.$statistic->id, $editedStatistic);

        $this->assertApiResponse($editedStatistic);
    }

    /**
     * @test
     */
    public function testDeleteStatistic()
    {
        $statistic = $this->makeStatistic();
        $this->json('DELETE', '/api/v1/statistics/'.$statistic->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/statistics/'.$statistic->id);

        $this->assertResponseStatus(404);
    }
}
