<?php

namespace App\Console\Commands;

use App\Models\Viewing;
use App\Repositories\ScoreRepository;

class ViewingUpdate extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'viewing:timeElapsed';
  protected $now;
  protected $scoringRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check elapse viewing to see if there is noshow';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->now = \Carbon\Carbon::now();
    $this->scoreRepository = \App::make(ScoreRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->logInfo(sprintf("ViewingUpdate %s", $this->now->toDateTimeString()));

    // marking noshow for confirm viewing
    $this->updateViewing();
    // marking passed for viewing that didnt occur
    $this->tagViewAsPassed();

    $this->deletedPassedViewing();
  }

  protected function updateViewing() {
    $this->logInfo(sprintf("ViewingUpdate updateViewing %s", $this->now->toDateTimeString()));
    $confirmViewings = Viewing::where('status', Viewing::CONFIRM)
        ->where('end_datetime', '<', $this->now->toDateTimeString())
        ->with('confirmRequests')
        ->get();

    foreach ($confirmViewings as $viewing) {
      try {
        $viewing->status = Viewing::DONE;
        $viewing->save();
      } catch (Exception $e) {
        $this->error('Error ViewingUpdate updateViewing ' . $e->getMessage());
      }
    }
  }

  protected function tagViewAsPassed() {
    $this->logInfo(sprintf("ViewingUpdate tagViewAsPassed %s", $this->now->toDateTimeString()));
    
    $viewings = Viewing::where('status', Viewing::AVAILABLE)
        ->where('end_datetime', '<', $this->now->toDateTimeString())
        ->get();

    foreach ($viewings as $viewing) {
      try {
        $viewing->status = Viewing::PASSED;
        $viewing->save();
      } catch (Exception $e) {
        $this->error('Error ViewingUpdate tagViewAsPassed ' . $e->getMessage());
      }
    }
  }

  protected function deletedPassedViewing() {
    $this->logInfo(sprintf("ViewingUpdate deletedPassedViewing %s", $this->now->toDateTimeString()));
    
    $viewings = Viewing::where('status', Viewing::PASSED)
        ->where('end_datetime', '<', $this->now->subDays(30)->toDateTimeString())
        ->get();

    foreach ($viewings as $viewing) {
      try {
        $viewing->delete();
      } catch (Exception $e) {
        $this->error('Error ViewingUpdate deletedPassedViewing ' . $e->getMessage());
      }
    }
  }
}
