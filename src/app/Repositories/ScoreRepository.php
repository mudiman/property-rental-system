<?php

namespace App\Repositories;

use App\Models\Score;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\AggregateUserScore;

class ScoreRepository extends ParentRepository
{
    use DispatchesJobs;
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'score_type_id',
        'user_id',
        'scoreable_id',
        'scoreable_type',
        'status',
        'score',
        'score_change',
        'current',
        'max',
        'min',
        'factor',
        'streak_count',
        'max_diff',
        'comment',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Score::class;
    }
    
    public function calculateScore($type, $current, $max, $min, $maxPercentage, $minPercentage, $streak_count, $factor) 
    {
      $max_diff = $min_diff = $max - $current;
      if ($max_diff <= 0) {
        $max_diff = $minPercentage;
      }
      if ($min_diff >= $maxPercentage) {
        $min_diff = $maxPercentage;
      }
      $score_change = 0;
      if ($type) {
        $score_change = ((($streak_count * config('business.scoring.streak_impact')) + ($min_diff * $maxPercentage * $factor)) * $current);
        $score =  $score_change + $current;
        $streak_count++;
      } else {
        $score_change = (($streak_count * config('business.scoring.streak_impact')) * ($max_diff * $maxPercentage * $factor) * $current);
        $score = $current - $score_change;
        $streak_count--;
        if ($streak_count < 0) $streak_count = 0;
      }
      if ($score > $max) {
        $max = $score;
      }
      if ($score < $min) {
        $min = $score;
      }
      return [
        number_format($score,2, '.', ''), 
        number_format($min,2, '.', ''), 
        number_format($max,2, '.', ''), 
        number_format($score_change,2, '.', ''), 
        $streak_count
      ];
  }
  
    public function dispatchNotification($config, $model, $from, $to)
    {
      (new \App\Support\Notification($config, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'scoreable_id' => $model->scoreable_id,
        'scoreable_type' => $model->scoreable_type,
        'status' => $model->status,
        'current' => $model->current,
        'score_change' => $model->score_change,
        'messageId' => $model->id,
        'messageType' => Score::morphClass,
        'snapshot' => json_encode($model->toArray())
      ]))->notify();
    }
}
