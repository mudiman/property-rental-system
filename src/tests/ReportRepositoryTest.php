<?php

use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportRepositoryTest extends TestCase
{
    use MakeReportTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ReportRepository
     */
    protected $reportRepo;

    public function setUp()
    {
        parent::setUp();
        $this->reportRepo = App::make(ReportRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateReport()
    {
        $report = $this->fakeReportData();
        $createdReport = $this->reportRepo->create($report);
        $createdReport = $createdReport->toArray();
        $this->assertArrayHasKey('id', $createdReport);
        $this->assertNotNull($createdReport['id'], 'Created Report must have id specified');
        $this->assertNotNull(Report::find($createdReport['id']), 'Report with given id must be in DB');
        $this->assertModelData($report, $createdReport);
    }

    /**
     * @test read
     */
    public function testReadReport()
    {
        $report = $this->makeReport();
        $dbReport = $this->reportRepo->find($report->id);
        $dbReport = $dbReport->toArray();
        $this->assertModelData($report->toArray(), $dbReport);
    }

    /**
     * @test update
     */
    public function testUpdateReport()
    {
        $report = $this->makeReport();
        $fakeReport = $this->fakeReportData();
        $updatedReport = $this->reportRepo->update($fakeReport, $report->id);
        $this->assertModelData($fakeReport, $updatedReport->toArray());
        $dbReport = $this->reportRepo->find($report->id);
        $this->assertModelData($fakeReport, $dbReport->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteReport()
    {
        $report = $this->makeReport();
        $resp = $this->reportRepo->delete($report->id);
        $this->assertTrue($resp);
        $this->assertNull(Report::find($report->id), 'Report should not exist in DB');
    }
}
