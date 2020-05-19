<?php

namespace App\Repositories;

use \InfyOm\Generator\Common\BaseRepository;

abstract class ParentRepository extends BaseRepository
{
  abstract public function dispatchNotification($notificationConfig, $model, $from, $to);
}
