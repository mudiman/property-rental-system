<?php

use Faker\Factory as Faker;
use App\Models\PropertyService;
use App\Repositories\PropertyServiceRepository;

trait MakePropertyServiceTrait
{
    /**
     * Create fake instance of PropertyService and save it in database
     *
     * @param array $propertyServiceFields
     * @return PropertyService
     */
    public function makePropertyService($propertyServiceFields = [])
    {
        /** @var PropertyServiceRepository $propertyServiceRepo */
        $propertyServiceRepo = App::make(PropertyServiceRepository::class);
        $theme = $this->fakePropertyServiceData($propertyServiceFields);
        return $propertyServiceRepo->create($theme);
    }

    /**
     * Get fake instance of PropertyService
     *
     * @param array $propertyServiceFields
     * @return PropertyService
     */
    public function fakePropertyService($propertyServiceFields = [])
    {
        return new PropertyService($this->fakePropertyServiceData($propertyServiceFields));
    }

    /**
     * Get fake data of PropertyService
     *
     * @param array $postFields
     * @return array
     */
    public function fakePropertyServiceData($propertyServiceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'property_pro_entity_id' => $fake->word,
            'user_id' => $fake->word,
            'property_id' => $fake->word,
            'service_id' => $fake->word,
            'status' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $propertyServiceFields);
    }
}
