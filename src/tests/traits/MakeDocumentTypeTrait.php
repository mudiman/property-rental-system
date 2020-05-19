<?php

use Faker\Factory as Faker;
use App\Models\DocumentType;
use App\Repositories\DocumentTypeRepository;

trait MakeDocumentTypeTrait
{
    /**
     * Create fake instance of DocumentType and save it in database
     *
     * @param array $documentTypeFields
     * @return DocumentType
     */
    public function makeDocumentType($documentTypeFields = [])
    {
        /** @var DocumentTypeRepository $documentTypeRepo */
        $documentTypeRepo = App::make(DocumentTypeRepository::class);
        $theme = $this->fakeDocumentTypeData($documentTypeFields);
        return $documentTypeRepo->create($theme);
    }

    /**
     * Get fake instance of DocumentType
     *
     * @param array $documentTypeFields
     * @return DocumentType
     */
    public function fakeDocumentType($documentTypeFields = [])
    {
        return new DocumentType($this->fakeDocumentTypeData($documentTypeFields));
    }

    /**
     * Get fake data of DocumentType
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDocumentTypeData($documentTypeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'type' => $fake->word,
            'is_active' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $documentTypeFields);
    }
}
