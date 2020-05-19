<?php

namespace App\Repositories;

use App\Models\DocumentType;

class DocumentTypeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'type',
        'is_active',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DocumentType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
