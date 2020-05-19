<?php

use Faker\Factory as Faker;
use App\Models\Document;
use App\Repositories\DocumentRepository;

trait MakeDocumentTrait
{
    /**
     * Create fake instance of Document and save it in database
     *
     * @param array $documentFields
     * @return Document
     */
    public function makeDocument($documentFields = [])
    {
        /** @var DocumentRepository $documentRepo */
        $documentRepo = App::make(DocumentRepository::class);
        $theme = $this->fakeDocumentData($documentFields);
        return $documentRepo->create($theme);
    }

    /**
     * Get fake instance of Document
     *
     * @param array $documentFields
     * @return Document
     */
    public function fakeDocument($documentFields = [])
    {
        return new Document($this->fakeDocumentData($documentFields));
    }

    /**
     * Get fake data of Document
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDocumentData($documentFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'type' => $fake->word,
            'mimetype' => $fake->word,
            'issuing_country' => $fake->word,
            'verified' => $fake->word,
            'path' => $fake->word,
            'filename' => $fake->word,
            'file_front_path' => $fake->word,
            'file_front_filename' => $fake->word,
            'file_front_mimetype' => $fake->word,
            'file_back_path' => $fake->word,
            'file_back_filename' => $fake->word,
            'file_back_mimetype' => $fake->word,
            'documentable_id' => $fake->randomDigitNotNull,
            'documentable_type' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s')
        ], $documentFields);
    }
}
