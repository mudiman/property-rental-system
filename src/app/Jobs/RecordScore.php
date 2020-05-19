<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Repositories\ScoreRepository;
use App\Models\ScoreType;
use App\Models\Score;
use App\Models\User;

class RecordScore implements ShouldQueue {

  use Dispatchable,
      InteractsWithQueue,
      Queueable,
      SerializesModels;

  private $userId;
  private $id;
  private $scoreable_type;
  private $scoreTypeId;
  private $type;
  private $factor;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($userId, $id, $scoreable_type, $scoreTypeId, $type, $factor) {

    $this->userId = $userId;
    $this->id = $id;
    $this->scoreable_type = $scoreable_type;
    $this->scoreTypeId = $scoreTypeId;
    $this->type = $type;
    $this->factor = $factor;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {
    $scoringRepository = \App::make(ScoreRepository::class);
    $lastScore = Score::where('score_type_id', $this->scoreTypeId)
        ->where('user_id', $this->userId)
        ->orderBy('created_at', 'desc')
        ->with('scoreType')
        ->with('user')
        ->first();
    
    $currentMax = config('business.scoring.max_score');
    $currentMin = config('business.scoring.min_score');
    $currentScore = config('business.scoring.start_score');
    $currentStreakCount = 0;
    
    if (empty($lastScore)) {
      $user = User::findorfail($this->userId);
      $scoreType = ScoreType::findorfail($this->scoreTypeId);
      // set current score to be category score of user if user hasnt perform this score type yet
      if ($user->{$scoreType->category.'_reputation'} != 0) {
        $currentScore = $user->{$scoreType->category.'_reputation'};
      }
    } else {
      $scoreType = $lastScore->scoreType;
      $user = $lastScore->user;
      $currentMax = $lastScore->max;
      $currentMin = $lastScore->min;
      $currentScore = $lastScore->current;
      $currentStreakCount = $lastScore->streak_count;
    }
    list($score, $min, $max, $score_change, $streak_count) = $scoringRepository->calculateScore(
          $this->type, 
          $currentScore, 
          $currentMax, 
          $currentMin, 
          $scoreType->max_percentage, 
          $scoreType->min_percentage, 
          $currentStreakCount, 
          $this->factor);
    
    $input = [
      'scoreable_id' => $this->id,
      'scoreable_type' => $this->scoreable_type,
      'user_id' => $this->userId,
      'score_type_id' => $this->scoreTypeId,
      'score' => $score,
      'min' => $min,
      'max' => $max,
      'score_change' => $score_change,
      'streak_count' => $streak_count,
      'current' => $score,
    ];
    $scoringRepository->create($input);
    
    $newScore = number_format($score, 2, '.', '');
    $categoryField = $scoreType->category.'_reputation';
    
    $user->{$categoryField} = $newScore;
    $user->{$categoryField."_max"} = number_format($max, 2, '.', '');
    $user->{$categoryField."_min"} = number_format($min, 2, '.', '');
    $user->save();
    
    Log::info(sprintf('Registering score for user id %s with score %s for category %s', $this->userId, $newScore, $scoreType->category));
  }

}
