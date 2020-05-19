<?php

use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PropertyRepositoryTest extends TestCase
{
    use MakePropertyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PropertyRepository
     */
    protected $propertyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->propertyRepo = App::make(PropertyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateProperty()
    {
        $property = $this->fakePropertyData();
        $createdProperty = $this->propertyRepo->create($property);
        $createdProperty = $createdProperty->toArray();
        $this->assertArrayHasKey('id', $createdProperty);
        $this->assertNotNull($createdProperty['id'], 'Created Property must have id specified');
        $this->assertNotNull(Property::find($createdProperty['id']), 'Property with given id must be in DB');
        $this->assertModelData($property, $createdProperty);
    }

    /**
     * @test read
     */
    public function testReadProperty()
    {
        $property = $this->makeProperty();
        $dbProperty = $this->propertyRepo->find($property->id);
        $dbProperty = $dbProperty->toArray();
        $this->assertModelData($property->toArray(), $dbProperty);
    }

    /**
     * @test update
     */
    public function testUpdateProperty()
    {
        $property = $this->makeProperty();
        $fakeProperty = $this->fakePropertyData();
        $updatedProperty = $this->propertyRepo->update($fakeProperty, $property->id);
        $this->assertModelData($fakeProperty, $updatedProperty->toArray());
        $dbProperty = $this->propertyRepo->find($property->id);
        $this->assertModelData($fakeProperty, $dbProperty->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteProperty()
    {
        $property = $this->makeProperty();
        $resp = $this->propertyRepo->delete($property->id);
        $this->assertTrue($resp);
        $this->assertNull(Property::find($property->id), 'Property should not exist in DB');
    }
}
