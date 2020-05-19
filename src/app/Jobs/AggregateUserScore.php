<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Score;

class AggregateUserScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info(sprintf("Aggregate score for user %s.", $this->user_id));
      
      $user = User::findorfail($this->user_id);
      $scores = Score::where('user_id', $this->user_id)->with('scoreType')->get();
      
      // combine same scoring type ids into one array for averaging them
      $score_type_wise = [];
      foreach($scores as $score) {
        if (!isset($score_type_wise[$score->score_type_id])) {
          $score_type_wise[$score->score_type_id] = [];
        }
        $score_type_wise[$score->score_type_id][] = $score;
      }
      Log::info(sprintf("score_type_wise %s.", json_encode($score_type_wise)));
      
      //avg same scoring type id
      $score_type_wise_avg = [];
      foreach($score_type_wise as $score_types) {
        $total = 0;
        $score = 0;
        foreach($score_types as $score_type) {
          $score += $score_type->score;
          $total++;
        }
        $avg_score = $score_type;
        $avg_score->score = $score/$total;
        $score_type_wise_avg[] = $avg_score;
      }
      Log::info(sprintf("score_type_wise_avg %s.", json_encode($score_type_wise_avg)));
      
      // combine same scoring category based on weightage
      $score_category_wise = [];
      $score_total_category_wise = [];
      foreach($score_type_wise_avg as $score) {
        $scoreType = $score->scoreType;
        if (!isset($score_category_wise[$scoreType->category])) {
          $score_category_wise[$scoreType->category] = 0;
          $score_total_category_wise[$scoreType->category] = 0;
        }
        $score_category_wise[$scoreType->category] += $scoreType->weight * $score->score;
        $score_total_category_wise[$scoreType->category] += $scoreType->weight * config("business.scoring.max_score");
      }
      Log::info(sprintf("score_total_category_wise %s.", json_encode($score_total_category_wise)));
      
      foreach($score_category_wise  as $key => $value) {
        $user->{$key.'_reputation'} = number_format($value/$score_total_category_wise[$key] * 100, 2);
      }
      $user->save();
    }
}
