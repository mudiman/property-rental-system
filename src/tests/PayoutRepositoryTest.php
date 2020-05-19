<?php

use App\Models\Payout;
use App\Repositories\PayoutRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayoutRepositoryTest extends TestCase
{
    use MakePayoutTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PayoutRepository
     */
    protected $payoutRepo;

    public function setUp()
    {
        parent::setUp();
        $this->payoutRepo = App::make(PayoutRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePayout()
    {
        $payout = $this->fakePayoutData();
        $createdPayout = $this->payoutRepo->create($payout);
        $createdPayout = $createdPayout->toArray();
        $this->assertArrayHasKey('id', $createdPayout);
        $this->assertNotNull($createdPayout['id'], 'Created Payout must have id specified');
        $this->assertNotNull(Payout::find($createdPayout['id']), 'Payout with given id must be in DB');
        $this->assertModelData($payout, $createdPayout);
    }

    /**
     * @test read
     */
    public function testReadPayout()
    {
        $payout = $this->makePayout();
        $dbPayout = $this->payoutRepo->find($payout->id);
        $dbPayout = $dbPayout->toArray();
        $this->assertModelData($payout->toArray(), $dbPayout);
    }

    /**
     * @test update
     */
    public function testUpdatePayout()
    {
        $payout = $this->makePayout();
        $fakePayout = $this->fakePayoutData();
        $updatedPayout = $this->payoutRepo->update($fakePayout, $payout->id);
        $this->assertModelData($fakePayout, $updatedPayout->toArray());
        $dbPayout = $this->payoutRepo->find($payout->id);
        $this->assertModelData($fakePayout, $dbPayout->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePayout()
    {
        $payout = $this->makePayout();
        $resp = $this->payoutRepo->delete($payout->id);
        $this->assertTrue($resp);
        $this->assertNull(Payout::find($payout->id), 'Payout should not exist in DB');
    }
}
