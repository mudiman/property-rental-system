<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      \App\Console\Commands\UpComingReminder::class,
      \App\Console\Commands\ViewingUpdate::class,
      \App\Console\Commands\CreateUpcomingTransaction::class,
      \App\Console\Commands\RecurringPayment::class,
      \App\Console\Commands\TenancyUpdate::class,
      \App\Console\Commands\TenancyCheckInNotification::class,
      \App\Console\Commands\TenancyNoticesReminder::class,
      \App\Console\Commands\TenancyLandlordPropertyProTDSReminder::class,
      //\App\Console\Commands\TenancyTenantPayoutUpdateReminder::class,
      \App\Console\Commands\RetryFailedTransaction::class,
      \App\Console\Commands\OfferUpdate::class,
      
      \App\Console\Commands\Test\ProcessTenancy::class,
      \App\Console\Commands\Test\TimeShiftTenancy::class,
      
      \App\Console\Commands\Import\ImportLandlord::class,
      \App\Console\Commands\Import\ImportTenant::class,
      \App\Console\Commands\Import\ImportProperty::class,
      \App\Console\Commands\Import\ImportPropertyImages::class,
      \App\Console\Commands\Import\ImportUserDocs::class,
      \App\Console\Commands\Import\ImportAddressCoordinates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $environment = App::environment();
      if (in_array($environment,['local', 'dev'])) {
        $this->scheduleDev($schedule);
      } elseif (in_array($environment, ['stage', 'liveadmin'])) {
        $this->scheduleLive($schedule);
      }
    }
    
    protected function scheduleLive(Schedule $schedule)
    {
      $schedule->command('viewing:timeElapsed')->withoutOverlapping()->everyFiveMinutes();
      $schedule->command('reminder:upcoming')->withoutOverlapping()->everyThirtyMinutes();
      
      $schedule->command('payment:create-upcoming-transaction')->withoutOverlapping()->daily();
      $schedule->command('payment:recurring')->withoutOverlapping()->cron('0 */8 * * *'); // every 8 hour
      $schedule->command('payment:retryFailed')->withoutOverlapping()->cron('0 */12 * * *');
      
      $schedule->command('tenancy:timeElapsed')->withoutOverlapping()->cron('0 */8 * * *'); // every 8 hour
      $schedule->command('tenancy:noticesReminder')->withoutOverlapping()->daily();
      $schedule->command('tenancy:landlordPropertyProTDSReminder')->withoutOverlapping()->daily();
      
      $schedule->command('offer:timeElapsed')->cron('0 */4 * * *'); // every 4 hour
    }
    
    protected function scheduleDev(Schedule $schedule)
    {
      $schedule->command('viewing:timeElapsed')->withoutOverlapping()->everyFiveMinutes();
      
      $schedule->command('reminder:upcoming')->withoutOverlapping()->everyThirtyMinutes();
      
      $schedule->command('payment:create-upcoming-transaction')->withoutOverlapping()->hourly();
      $schedule->command('payment:recurring')->withoutOverlapping()->hourly();
      $schedule->command('payment:retryFailed')->withoutOverlapping()->hourly();
      
      $schedule->command('tenancy:timeElapsed')->withoutOverlapping()->hourly();
      $schedule->command('tenancy:noticesReminder')->withoutOverlapping()->daily();
      $schedule->command('tenancy:landlordPropertyProTDSReminder')->withoutOverlapping()->daily();
      
      $schedule->command('offer:timeElapsed')->withoutOverlapping()->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
