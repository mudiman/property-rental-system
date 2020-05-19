<?php

use App\Models\Statistic;
use App\Repositories\StatisticRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatisticRepositoryTest extends TestCase
{
    use MakeStatisticTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var StatisticRepository
     */
    protected $statisticRepo;

    public function setUp()
    {
        parent::setUp();
        $this->statisticRepo = App::make(StatisticRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateStatistic()
    {
        $statistic = $this->fakeStatisticData();
        $createdStatistic = $this->statisticRepo->create($statistic);
        $createdStatistic = $createdStatistic->toArray();
        $this->assertArrayHasKey('id', $createdStatistic);
        $this->assertNotNull($createdStatistic['id'], 'Created Statistic must have id specified');
        $this->assertNotNull(Statistic::find($createdStatistic['id']), 'Statistic with given id must be in DB');
        $this->assertModelData($statistic, $createdStatistic);
    }

    /**
     * @test read
     */
    public function testReadStatistic()
    {
        $statistic = $this->makeStatistic();
        $dbStatistic = $this->statisticRepo->find($statistic->id);
        $dbStatistic = $dbStatistic->toArray();
        $this->assertModelData($statistic->toArray(), $dbStatistic);
    }

    /**
     * @test update
     */
    public function testUpdateStatistic()
    {
        $statistic = $this->makeStatistic();
        $fakeStatistic = $this->fakeStatisticData();
        $updatedStatistic = $this->statisticRepo->update($fakeStatistic, $statistic->id);
        $this->assertModelData($fakeStatistic, $updatedStatistic->toArray());
        $dbStatistic = $this->statisticRepo->find($statistic->id);
        $this->assertModelData($fakeStatistic, $dbStatistic->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteStatistic()
    {
        $statistic = $this->makeStatistic();
        $resp = $this->statisticRepo->delete($statistic->id);
        $this->assertTrue($resp);
        $this->assertNull(Statistic::find($statistic->id), 'Statistic should not exist in DB');
    }
}
