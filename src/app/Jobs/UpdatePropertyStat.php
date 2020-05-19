<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Statistic;
use App\Models\Property;

class UpdatePropertyStat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $propertyId;
    private $userId;
    private $viewType;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($propertyId, $userId, $viewType)
    {
        $this->propertyId = $propertyId;
        $this->userId = $userId;
        $this->viewType = $viewType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $statistic = new Statistic();
      $statistic->user_id = $this->userId;
      $statistic->property_id = $this->propertyId;
      $statistic->view_type = $this->viewType;
      $statistic->save();

      $property = Property::findorfail($this->propertyId);
      $property->total_listing_view++;
      $property->total_detail_view++;
      $property->save();
    }
}
