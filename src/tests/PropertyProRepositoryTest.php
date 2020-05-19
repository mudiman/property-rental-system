<?php

use App\Models\PropertyPro;
use App\Repositories\PropertyProRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyProRepositoryTest extends TestCase
{
    use MakePropertyProTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PropertyProRepository
     */
    protected $propertyProRepo;

    public function setUp()
    {
        parent::setUp();
        $this->propertyProRepo = App::make(PropertyProRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePropertyPro()
    {
        $propertyPro = $this->fakePropertyProData();
        $createdPropertyPro = $this->propertyProRepo->create($propertyPro);
        $createdPropertyPro = $createdPropertyPro->toArray();
        $this->assertArrayHasKey('id', $createdPropertyPro);
        $this->assertNotNull($createdPropertyPro['id'], 'Created PropertyPro must have id specified');
        $this->assertNotNull(PropertyPro::find($createdPropertyPro['id']), 'PropertyPro with given id must be in DB');
        $this->assertModelData($propertyPro, $createdPropertyPro);
    }

    /**
     * @test read
     */
    public function testReadPropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $dbPropertyPro = $this->propertyProRepo->find($propertyPro->id);
        $dbPropertyPro = $dbPropertyPro->toArray();
        $this->assertModelData($propertyPro->toArray(), $dbPropertyPro);
    }

    /**
     * @test update
     */
    public function testUpdatePropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $fakePropertyPro = $this->fakePropertyProData();
        $updatedPropertyPro = $this->propertyProRepo->update($fakePropertyPro, $propertyPro->id);
        $this->assertModelData($fakePropertyPro, $updatedPropertyPro->toArray());
        $dbPropertyPro = $this->propertyProRepo->find($propertyPro->id);
        $this->assertModelData($fakePropertyPro, $dbPropertyPro->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePropertyPro()
    {
        $propertyPro = $this->makePropertyPro();
        $resp = $this->propertyProRepo->delete($propertyPro->id);
        $this->assertTrue($resp);
        $this->assertNull(PropertyPro::find($propertyPro->id), 'PropertyPro should not exist in DB');
    }
}
