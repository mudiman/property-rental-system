<?php 

namespace App\Observers;
use \Illuminate\Support\Facades\Auth;

class BaseObserver {


    public function __construct(){
    }

    public function updating($model)
    {
        if(Auth::check()) {
          $model->updated_by = Auth::user()->id;
        } else {
          $model->updated_by = NULL;
        }
    }

    public function creating($model)
    {
      if(Auth::check()) {
        $model->created_by = Auth::user()->id;
      } else {
        $model->created_by = NULL;
      }
    }

    public function deleting($model)
    {
        if(Auth::check()) {
          $model->deleted_by = Auth::user()->id;
        } else {
          $model->deleted_by = NULL;
        }
    }
}