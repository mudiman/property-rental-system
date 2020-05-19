<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;

class UpdateUserRecentSearchQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $userId;
    private $query;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $query)
    {
        $this->userId = $userId;
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $user = User::findorfail($this->userId);
      $user->recent_search_query = $this->query;
      $user->save();
    }
}
