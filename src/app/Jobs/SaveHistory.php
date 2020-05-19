<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Repositories\HistoryRepository;


class SaveHistory implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    protected $id;
    protected $morphclass;
    protected $dump;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $morphclass, $dump)
    {
        $this->id = $id;
        $this->morphclass = $morphclass;
        $this->dump = $dump;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      try {
        $historyRepository = \App::make(HistoryRepository::class);
        $input = [
          'historiable_id' => $this->id,
          'historiable_type' => $this->morphclass,
          'snapshot' => $this->dump,
        ];
        $historyRepository->create($input);
        Log::info(sprintf("SaveHistory %s .", $this->morphclass));
      } catch(Exception $e) {
          Log::Error('Error SaveHistory '. $e->getMessage());
      }
    }
}
