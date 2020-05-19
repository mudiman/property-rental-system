<?php

namespace App\Observers;

use App\Models\Agency;

class AgencyObserver extends ParentObserver 
{

  public function deleted(Agency $model)
  {
      $model->agents()->delete();
  }

  public function restored(Agency $model)
  {
      $model->agents()->restore();
  }

}
