<?php

namespace App\Jobs\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Payout;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class MakePayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $payoutId;
    private $transactionId;
    private $parameters;
    
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payoutId, $transactionId, $parameters = [])
    {
        $this->payoutId = $payoutId;
        $this->transactionId = $transactionId;
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $payinRepository = \App::make(PayinRepository::class);
       
       $payin = Payout::findorfail($this->payoutId);
       $transaction = Transaction::findorfail($this->transactionId);
       
       $payinRepository->payoutTransaction($payin, $transaction, $this->parameters);
    }
}
