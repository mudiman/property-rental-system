<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @SWG\Definition(
 *      definition="Setting",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="setting",
 *          description="setting",
 *          type="string"
 *      )
 * )
 */
class Setting extends BaseModel
{
    
    public $table = 'settings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public $fillable = [
        'setting'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'setting' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer'
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];
    
    public static function boot() {
        parent::boot();
    }

    public static function storeInCache($model) {
        $settings = json_decode($model->setting);
        Cache::forever('setting', $settings);
        return $settings;
    }

}
