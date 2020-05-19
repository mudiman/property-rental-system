<?php

use Faker\Factory as Faker;
use App\Models\Reference;
use App\Repositories\ReferenceRepository;

trait MakeReferenceTrait
{
    /**
     * Create fake instance of Reference and save it in database
     *
     * @param array $referenceFields
     * @return Reference
     */
    public function makeReference($referenceFields = [])
    {
        /** @var ReferenceRepository $referenceRepo */
        $referenceRepo = App::make(ReferenceRepository::class);
        $theme = $this->fakeReferenceData($referenceFields);
        return $referenceRepo->create($theme);
    }

    /**
     * Get fake instance of Reference
     *
     * @param array $referenceFields
     * @return Reference
     */
    public function fakeReference($referenceFields = [])
    {
        return new Reference($this->fakeReferenceData($referenceFields));
    }

    /**
     * Get fake data of Reference
     *
     * @param array $postFields
     * @return array
     */
    public function fakeReferenceData($referenceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'by_user' => $fake->randomDigitNotNull,
            'for_user' => $fake->randomDigitNotNull,
            'comment' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s')
        ], $referenceFields);
    }
}
