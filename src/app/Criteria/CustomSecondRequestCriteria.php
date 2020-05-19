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
class CustomSecondRequestCriteria implements CriteriaInterface
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
        $fieldsSearchable = $repository->getFieldsSearchable();
        $search = $this->request->get(config('repository.criteria.params.where', 'where'), null);
        $searchFields = $this->request->get(config('repository.criteria.params.whereFields', 'whereFields'), null);
        $filter = $this->request->get(config('repository.criteria.params.filter2', 'filter2'), null);
        $orderBy = $this->request->get(config('repository.criteria.params.orderBy2', 'orderBy2'), null);
        $sortedBy = $this->request->get(config('repository.criteria.params.sortedBy2', 'sortedBy2'), 'asc');
        $with = $this->request->get(config('repository.criteria.params.with2', 'with2'), null);
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'asc';
        
        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {
            
            $searchFields = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';', $searchFields);
            $fields = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField = true;
            $searchData = $this->parserSearchData($search);
            $search = $this->parserSearchValue($search);
            $combineBy = $this->request->get(config('repository.criteria.params.mergeBy', 'mergeBy'), 'and');
            $modelForceAndWhere = $this->parserCombineBy($combineBy);
            
            $model = $model->where(function ($query) use ($fields, $search, $searchData, $isFirstField, $modelForceAndWhere) {
                /** @var Builder $query */

                foreach ($fields as $field => $condition) {

                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = "=";
                    }

                    $value = null;

                    $condition = trim(strtolower($condition));

                    if (isset($searchData[$field])) {
                        $value = ($condition == "like" || $condition == "ilike") ? "%{$searchData[$field]}%" : $searchData[$field];
                    } else {
                        if (!is_null($search)) {
                            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                        }
                    }

                    $relation = null;
                    if(stripos($field, '.')) {
                        $explode = explode('.', $field);
                        $field = array_pop($explode);
                        $relation = implode('.', $explode);
                    }
                    $modelTableName = $query->getModel()->getTable();
                    
                    if ( $isFirstField || $this->checkCombineBy($field, $modelForceAndWhere)) {
                        if (!is_null($value)) {
                            if(!is_null($relation)) {
                                $query->whereHas($relation, function($query) use($field,$condition,$value) {
                                    $query->where(explode("$$",$field)[0],$condition,$value);
                                });
                            } else {
                                $query->where($modelTableName.'.'.explode("$$",$field)[0],$condition,$value);
                            }
                            $isFirstField = false;
                        }
                    } else {
                        if (!is_null($value)) {
                            if(!is_null($relation)) {
                                $query->orWhereHas($relation, function($query) use($field,$condition,$value) {
                                    $query->where(explode("$$",$field)[0],$condition,$value);
                                });
                            } else {
                                $query->orWhere($modelTableName.'.'.explode("$$",$field)[0], $condition, $value);
                            }
                        }
                    }
                }
            });
        }
        
        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode('|', $orderBy);
            if(count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products on current_table.product_id = products.id order by description
                 * 
                 * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
                 * by products.description (in case both tables have same column name)
                 */
                $table = $model->getModel()->getTable();
                $sortTable = $split[0];
                $sortColumn = $split[1];

                $split = explode(':', $sortTable);
                if(count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName = $table.'.'.$split[1];
                } else {
                    /*
                     * If you do not define which column to use as a joining column on current table, it will
                     * use a singular of a join table appended with _id
                     *
                     * ex.
                     * products -> product_id
                     */
                    $prefix = rtrim($sortTable, 's');
                    $keyName = $table.'.'.$prefix.'_id';
                }

                $model = $model
                    ->leftJoin($sortTable, $keyName, '=', $sortTable.'.id')
                    ->orderBy($sortColumn, $sortedBy)
                    ->addSelect($table.'.*');
            } else {
                $model = $model->orderBy($orderBy, $sortedBy);
            }
        }

        if (isset($filter) && !empty($filter)) {
            if (is_string($filter)) {
                $filter = explode(';', $filter);
            }

            $model = $model->select($filter);
        }

        if ($with) {
            $with = explode(';', $with);
            $model = $model->with($with);
        }

        return $model;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];
        if (stripos($search, ':')) {
            $fields = explode(';', $search);
            $index = 0;
            foreach ($fields as $row) {
                try {
                    $splitpos = strpos($row, ':');
                    $field = substr($row, 0, $splitpos);
                    $value = substr($row, $splitpos + 1);
                    $searchData[$field.'$$'.$index] = $value;
                    $index++;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }
        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {

        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }
    
    protected function checkCombineBy($field, $combineBy)
    {
      if (is_bool($combineBy)) {
        return $combineBy;
      }
      $fieldWIthSymbol = explode("$$",$field)[0];
      if ((isset($combineBy[$fieldWIthSymbol]) && $combineBy[$fieldWIthSymbol] == 'or')) {
        return false;
      }
      return true;
    }
    
    /**
     * @param $search
     *
     * @return null
     */
    protected function parserCombineBy($search)
    {
        $combineBy = [];
        if ($search == 'and') {
          return true;
        }
        if ($search == 'or') {
          return false;
        }
        if (stripos($search, ':')) {
            $fields = explode(';', $search);
            foreach ($fields as $row) {
                try {
                    $splitpos = strpos($row, ':');
                    $field = substr($row, 0, $splitpos);
                    $value = substr($row, $splitpos + 1);
                    $combineBy[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }
        return $combineBy;
    }


    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        if (!is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = config('repository.criteria.acceptedConditions', [
                '=',
                'like'
            ]);
            $originalFields = $fields;
            $fields = [];
            $index = 0;
            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field = $field_parts[0].'$$'.$index;
                        $condition = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index] = $field;
                        $index++;
                    }
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \Exception(trans('repository::criteria.fields_not_accepted', ['field' => implode(',', $searchFields)]));
            }

        }

        return $fields;
    }
}