<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @SWG\Definition(
 *      definition="Document",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mimetype",
 *          description="mimetype",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="issuing_country",
 *          description="issuing_country",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="verified",
 *          description="verified",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="path",
 *          description="path",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bucket_name",
 *          description="bucket_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="filename",
 *          description="filename",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_front_path",
 *          description="file_front_path",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_front_filename",
 *          description="file_front_filename",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_front_mimetype",
 *          description="file_front_mimetype",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_back_path",
 *          description="file_back_path",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_back_filename",
 *          description="file_back_filename",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_back_mimetype",
 *          description="file_back_mimetype",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="documentable_id",
 *          description="documentable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="documentable_type",
 *          description="documentable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="updated_by",
 *          description="updated_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_by",
 *          description="created_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="deleted_by",
 *          description="deleted_by",
 *          type="integer",
 *          format="int32"
 *      ),
 * )
 */
class Document extends BaseModel
{
    use SoftDeletes;
    
    public $table = 'documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const TYPE_TENANT_SIGNATURE = 'tenant_signature';
    const TYPE_LANDLORD_SIGNATURE = 'landlord_signature';
    
    const TYPE_PASSPORT = 'passport';
    const TYPE_DRIVING_LICENSE = 'driverlicense';
    const TYPE_IDENTITY_DOCUMENT = 'idcard';
    
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'name',
        'type',
        'mimetype',
        'issuing_country',
        'verified',
        'path',
        'bucket_name',
        'filename',
        'file_front_path',
        'file_front_filename',
        'file_front_mimetype',
        'file_back_path',
        'file_back_filename',
        'file_back_mimetype',
        'documentable_id',
        'documentable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string',
        'mimetype' => 'string',
        'issuing_country' => 'string',
        'verified' => 'boolean',
        'path' => 'string',
        'bucket_name' => 'string',
        'filename' => 'string',
        'file_front_path' => 'string',
        'file_front_filename' => 'string',
        'file_front_mimetype' => 'string',
        'file_back_path' => 'string',
        'file_back_filename' => 'string',
        'file_back_mimetype' => 'string',
        'documentable_id' => 'integer',
        'documentable_type' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $attributes = [
      'verified' => 0,
    ];

    /**
     * Validation rules
     * @var array
     */
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'name' => 'required',
        'type' => 'required',
        'documentable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'documentable_id' => 'required|poly_exists:documentable_type',
        'document_data' => 'required_without_all:file_front_data,file_front_data',
        'file_front_data' => 'required_without_all:document_data',
        'file_back_data' => 'required_without_all:document_data',
      ], $merge);
    }

    /**
     * Get all of the owning documentable models.
     */
    public function documentable()
    {
        return $this->morphTo();
    }
}
