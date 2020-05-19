<?php

namespace App\Console\Commands;

use App\Models\Offer;
use App\Repositories\OfferRepository;

class OfferUpdate extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'offer:timeElapsed';
  protected $now;
  protected $offerRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Trash expired offer';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->now = \Carbon\Carbon::now();
    $this->offerRepository = \App::make(OfferRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->logInfo(sprintf("OfferUpdate %s", $this->now->toDateTimeString()));
    $this->cancelExpiredOffer();
    $this->cancelInitialDepositTimeExpiredOffer();
    $this->deleteCancelOffer();
  }

  private function cancelExpiredOffer() {
    try {
      $this->logInfo(sprintf("OfferUpdate cancelExpiredOffer %s", $this->now->toDateTimeString()));
      
      $offers = Offer::where('offer_expiry', '<', $this->now->toDateTimeString())
          ->where('status', Offer::REQUEST)
          ->get();
      
      foreach ($offers as $offer) {
        $this->logInfo(sprintf("OfferUpdate cancelExpiredOffer canceling %s", $offer->id));
        $offer->status = Offer::CANCEL;
        $offer->save();
      }
    } catch (Exception $e) {
      $this->error('Error OfferUpdate cancelExpiredOffer ' . $e->getMessage());
    }
  }

  protected function cancelInitialDepositTimeExpiredOffer() {
    try {
      $this->logInfo(sprintf("OfferUpdate holding_deposit_expiry %s", $this->now->toDateTimeString()));
      
      $offers = Offer::where('holding_deposit_expiry', '<', $this->now->toDateTimeString())
          ->where('status', Offer::ACCEPT)
          ->get();

      foreach ($offers as $offer) {
        $this->logInfo(sprintf("OfferUpdate cancelInitialDepositTimeExpiredOffer canceling %s", $offer->id));
        $offer->status = Offer::CANCEL;
        $offer->save();
      }
    } catch (Exception $e) {
      $this->error('Error OfferUpdate cancelInitialDepositTimeExpiredOffer ' . $e->getMessage());
    }
  }
  
  protected function deleteCancelOffer() {
    try {
      $this->logInfo(sprintf("OfferUpdate deleteCancelOffer %s", $this->now->toDateTimeString()));
      
      $offers = Offer::with('previousOffer')->with('transactions')->with('tenancy')->with('reviews')
          ->where('updated_at', '<=', $this->now->subHours(24)->toDateTimeString())
          ->where('status', Offer::CANCEL)
          ->get();
      
      foreach ($offers as $offer) {
        $offer->delete();
      }
    } catch (Exception $e) {
      $this->error('Error OfferUpdate deleteCancelOffer ' . $e->getMessage());
    }
  }
}
