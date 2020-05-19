<?php

use App\Models\Payin;
use App\Repositories\PayinRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayinRepositoryTest extends TestCase
{
    use MakePayinTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PayinRepository
     */
    protected $payinRepo;

    public function setUp()
    {
        parent::setUp();
        $this->payinRepo = App::make(PayinRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePayin()
    {
        $payin = $this->fakePayinData();
        $createdPayin = $this->payinRepo->create($payin);
        $createdPayin = $createdPayin->toArray();
        $this->assertArrayHasKey('id', $createdPayin);
        $this->assertNotNull($createdPayin['id'], 'Created Payin must have id specified');
        $this->assertNotNull(Payin::find($createdPayin['id']), 'Payin with given id must be in DB');
        $this->assertModelData($payin, $createdPayin);
    }

    /**
     * @test read
     */
    public function testReadPayin()
    {
        $payin = $this->makePayin();
        $dbPayin = $this->payinRepo->find($payin->id);
        $dbPayin = $dbPayin->toArray();
        $this->assertModelData($payin->toArray(), $dbPayin);
    }

    /**
     * @test update
     */
    public function testUpdatePayin()
    {
        $payin = $this->makePayin();
        $fakePayin = $this->fakePayinData();
        $updatedPayin = $this->payinRepo->update($fakePayin, $payin->id);
        $this->assertModelData($fakePayin, $updatedPayin->toArray());
        $dbPayin = $this->payinRepo->find($payin->id);
        $this->assertModelData($fakePayin, $dbPayin->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePayin()
    {
        $payin = $this->makePayin();
        $resp = $this->payinRepo->delete($payin->id);
        $this->assertTrue($resp);
        $this->assertNull(Payin::find($payin->id), 'Payin should not exist in DB');
    }
}
