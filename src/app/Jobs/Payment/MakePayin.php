<?php

namespace App\Jobs\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Payin;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class MakePayin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $payinId;
    private $transactionId;
    private $parameters;
    
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payinId, $transactionId, $parameters = [])
    {
        $this->payinId = $payinId;
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
       $payinRepository = \App::make(TransactionRepository::class);
       
       $payin = Payin::findorfail($this->payinId);
       $transaction = Transaction::findorfail($this->transactionId);
       
       $payinRepository->payinTransaction($payin, $transaction, $this->parameters);
    }
}
