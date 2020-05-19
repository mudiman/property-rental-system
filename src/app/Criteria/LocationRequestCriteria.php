<?php

namespace App\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Description of CustomRequestCriteria
 *
 * @author muda
 */
class LocationRequestCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
      $geoSearch = $this->request->get(config('repository.criteria.params.geosearch', 'geosearch'), null);
      if ($geoSearch) {
        list($field, $location, $dist, $operator) = explode(":", $geoSearch);
        $model = $this->scopeDistance($model, $field, $dist, $location, $operator);
      }
      return $model;
    }
    
    public function scopeDistance($query, $field, $dist, $location, $operator)
    {
      list($lat, $long) = explode(",", $location);
        return $query->whereRaw("ABS(6371 * acos(cos(radians($long)) * cos(radians(x(cordinate))) * cos(radians(y(cordinate)) - radians($lat)) + sin(radians($long)) * sin(radians(x(cordinate))))) * 1000 $operator $dist");
 
    }
}