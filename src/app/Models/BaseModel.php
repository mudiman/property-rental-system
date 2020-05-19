<?php

namespace App\Models;

use Eloquent as Model;
use App\Observers\BaseObserver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaseModel extends Model {

  protected static function boot() {
    $class = get_called_class();
    $class::observe(new BaseObserver());
    
    parent::boot();
  }
  
  /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    **/
   public function createdBy()
   {
       return $this->belongsTo(\App\Models\User::class, 'created_by');
   }
   
   /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    **/
   public function updatedBy()
   {
       return $this->belongsTo(\App\Models\User::class, 'updated_by');
   }
   
   /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    **/
   public function deletedBy()
   {
       return $this->belongsTo(\App\Models\User::class, 'deleted_by');
   }
   
   protected function setCordinateMeta($cordinate, $attributeName) {
      if (empty($cordinate)) return;
      $this->attributes[$attributeName]  = DB::raw("POINT($cordinate)");
    }
    
    protected function getCordinateMeta($value) {
      if (empty($value)) return null;
      if (strpos($value, 'POINT') !== false) {
        $cordinate = ['order' => 1, 'type' => '1'];
        list($cordinate['lat'], $cordinate['lon']) = explode(",", str_replace(["POINT(", ")"], '', $value));
        return $cordinate;
      }
      return empty($value) ? null:unpack('x/x/x/x/corder/Ltype/dlat/dlon', $value);
    }
    
    protected function asDateTime($datetime)
    {
      return Carbon::parse($datetime);
    }
    
    protected function formatDateTime($dateTime)
    {
        return isset($dateTime) ? $dateTime->toIso8601String(): null;
    }

    protected function formatDate($date)
    {
        return isset($date) ? $date->format("Y-m-d"): null;
    }

    public function toArray()
    {
      $array = parent::toArray();
      foreach ($this->casts as $field => $value) {
        if ($value == 'datetime') {
          $array[$field] = $this->formatDateTime($this->{$field});
        }
        if ($value == 'date') {
          $array[$field] = $this->formatDate($this->{$field});
        }
      }
      return $array;
    }

}
