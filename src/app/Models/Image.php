<?php

namespace App\Models;

use App\Observers\ImageObserver;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @SWG\Definition(
 *      definition="Image",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
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
 *          property="primary",
 *          description="primary",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="position",
 *          description="position",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="imageable_id",
 *          description="imageable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="imageable_type",
 *          description="imageable_type",
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
 *      )
 * )
 */
class Image extends BaseModel
{
    use SoftDeletes;

    public $table = 'images';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'path',
        'bucket_name',
        'filename',
        'type',
        'mimetype',
        'primary',
        'position',
        'imageable_id',
        'imageable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'path' => 'string',
        'bucket_name' => 'string',
        'filename' => 'string',
        'type' => 'string',
        'mimetype' => 'string',
        'primary' => 'boolean',
        'position' => 'integer',
        'imageable_id' => 'integer',
        'imageable_type' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Validation rules
     * @var array
     */
    public static function rules($id = 0, $merge = []) {
      return array_merge([
        'imageable_type' => 'required|in:'.implode(",", array_keys(Relation::morphMap())),
        'imageable_id' => 'required|poly_exists:imageable_type',
        'type' => 'required',
        'image_data' => 'required',
      ], $merge);
    }
    
    protected $entity_types = [
        'user' => \App\Model\User::class,
        'property' => \App\Model\Property::class,
    ];
    
    public static function boot()
    {
        parent::boot();
        Image::observe(new ImageObserver());
    }
    
    /**
     * Get all of the owning imageable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
