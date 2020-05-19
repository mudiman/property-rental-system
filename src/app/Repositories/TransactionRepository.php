<?php

namespace App\Repositories;

use App\Models\Transaction;
use Propaganistas\LaravelIntl\Facades\Currency;
use App\Models\Payout;

class TransactionRepository extends ParentRepository
{

  /**
   * @var array
   */
  protected $fieldSearchable = [
    'user_id',
    'payin_id',
    'payout_id',
    'parent_transaction_id',
    'transactionable_id',
    'transactionable_type',
    'title',
    'description',
    'type',
    'amount',
    'currency',
    'smoor_commission',
    'payment_gateway_commission',
    'landlord_commission',
    'agency_commission',
    'property_pro_commission',
    'status',
    'transaction_data',
    'transaction_reference',
    'smoor_reference',
    'indempotent_key',
    'dividen_done',
    'payment_error_message',
    'payment_error_type',
    'payment_error_code',
    'payment_error_status',
    'payment_error_param',
    'payment_response',
    'refund_status',
    'refund_reference',
    'refund_response',
    'retries',
    'token_used',
    'due_date',
    'updated_by',
    'created_by',
    'deleted_by'
  ];

  /**
   * Configure the Model
   * */
  public function model() {
    return Transaction::class;
  }
  
  public function getOrCreatePayinTransaction($payin, $model, $currency, $amount, $title, $parameters = [])
  {
    $transaction = Transaction::where('title', $title)
      ->where('type', Transaction::TYPE_DEBIT)
      ->where('transactionable_id', $model->id)
      ->where('transactionable_type', $model->morphClass)
      ->first();
    
    if (empty($transaction)) {
      $transaction = $this->createPayinTransaction($payin, $model, $currency, $amount, $title, $parameters);
    }
    return $transaction;
  }
  
  public function createPayinTransaction($payin, $model, $currency, $amount, $title, $parameters = [])
  {
    $transaction = new Transaction();
    $transaction->type = Transaction::TYPE_DEBIT;
    $transaction->payin_id = $payin->id;
    $transaction->amount = $amount;
    $transaction->currency = $currency;
    $transaction->title = $title;
    $transaction->transactionable_id = $model->id;
    $transaction->transactionable_type = $model->morphClass;
    $transaction->indempotent_key = uniqid();
    foreach ($parameters as $key => $value) {
      $transaction->{$key} = $value;
    }
    $transaction->save();
    return $transaction;
  }

  public function payinTransaction($payin, $transaction, $parameters = []) {
    try {
      foreach ($parameters as $key => $value) {
        $transaction->{$key} = $value;
      }
      $transfer = \Stripe\Transfer::create(
        [
          "amount" => (int) $transaction->amount * 100,
          "currency" => $transaction->currency,
          "destination" => $payin->user_reference,
          "transfer_group" => $transaction->id
        ], [
           'indempotent_key' => $transaction->indempotent_key
      ]);
      $transaction->payment_response = json_encode($transfer);
      $transaction->transaction_reference = $transfer->id;
      $transaction->status = Transaction::STATUS_DONE;

    } catch (\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      $this->parsePaymentError($e, $transaction);
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      $this->parsePaymentError($e, $transaction);
    }
    
    $transaction->save();

    return $transaction;
  }
  
  public function getOrCreatePayoutTransaction($payout, $model, $currency, $amount, $title, $parameters = [], $token = null, $due_date = null)
  {
    $query = Transaction::where('title', $title)
      ->where('type', Transaction::TYPE_CREDIT)
      ->where('amount', $amount)
      ->where('transactionable_id', $model->id)
      ->where('transactionable_type', $model->morphClass);
    
    $setDueDate = isset($model->due_date) ? $model->due_date: null;
    if ($due_date) {
      $query->where('due_date', $due_date);
      $setDueDate = $due_date;
    }
    $transaction = $query->first();
    if (empty($transaction)) {
      $transaction = $this->createPayoutTransaction($payout, $model, $currency, $amount, $title, $token, $due_date, $parameters);
    } else {
      $transaction->payout_id = $payout->id;
      $transaction->amount = $amount;
      $transaction->currency = $currency;
      $transaction->due_date = $due_date;
      $transaction->token_used = isset($token) ? $token: $payout->token;
    }
    return $transaction;
  }
  
  private function createPayoutTransaction($payout, $model, $currency, $amount, $title, $token, $due_date, $parameters = [])
  {
    $transaction = new Transaction();
    $transaction->type = Transaction::TYPE_CREDIT;
    $transaction->payout_id = $payout->id;
    $transaction->amount = $amount;
    $transaction->currency = $currency;
    $transaction->title = $title;
    $transaction->token_used = $token;
    $transaction->due_date = $due_date;
    $transaction->transactionable_id = $model->id;
    $transaction->transactionable_type = $model->morphClass;
    $transaction->indempotent_key = uniqid();
    foreach ($parameters as $key => $value) {
      $transaction->{$key} = $value;
    }
    $transaction->save();
    return $transaction;
  }
  
  public function payoutTransaction($payout, $transaction, $parameters = []) {
    try {
      foreach ($parameters as $key => $value) {
        $transaction->{$key} = $value;
      }
      $charge = \Stripe\Charge::create(
        [
          "amount" => (int) $transaction->amount * 100,
          "currency" => $transaction->currency,
          "customer" => $payout->user_reference,
          "description" => $transaction->description,
        ], [
          'indempotent_key' => $transaction->indempotent_key
      ]);
      $transaction->payment_response = json_encode($charge);
      $transaction->transaction_reference = $charge->id;
      $transaction->status = Transaction::STATUS_DONE;
      
    } catch (\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $this->parsePaymentError($e, $transaction);
      $this->parsePaymentErrorSetPayout($e, $payout);
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      $this->parsePaymentError($e, $transaction);
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      $this->parsePaymentError($e, $transaction);
    }
    if ($transaction->token_used == $payout->token) {
      $payout->used = true;
      Payout::$FIRE_EVENTS = false;
      $payout->save();
    }
    $transaction->token_used = $payout->token;
    $transaction->save();

    return $transaction;
  }
  
  public function refundTransaction($transaction, $amount = null, $parameters = []) {
    try {
      foreach ($parameters as $key => $value) {
        $transaction->{$key} = $value;
      }
      //check first time refund
      if($transaction->refund_status == Transaction::STATUS_NONE){
        $transaction->status = Transaction::STATUS_REFUND;
        $transaction->refund_status = Transaction::STATUS_START;
        $transaction->indempotent_key = uniqid();
        $transaction->retries = 0;
      }
      $transaction->save();
      $param = [
        "charge" => $transaction->transaction_reference
      ];
      if (!$amount) {
        $param['amount'] = $amount;
      }
      $re = \Stripe\Refund::create($param);
      $transaction->refund_response = json_encode($re);
      $transaction->refund_reference = $re->id;
      $transaction->status = Transaction::STATUS_REFUND;
      
    } catch (\Stripe\Error\Card $e) {
      // Since it's a decline, \Stripe\Error\Card will be caught
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\RateLimit $e) {
      // Too many requests made to the API too quickly
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\InvalidRequest $e) {
      // Invalid parameters were supplied to Stripe's API
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Authentication $e) {
      // Authentication with Stripe's API failed
      // (maybe you changed API keys recently)
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network communication with Stripe failed
      $this->parsePaymentError($e, $transaction);
    } catch (\Stripe\Error\Base $e) {
      // Display a very generic error to the user, and maybe send
      // yourself an email
      $this->parsePaymentError($e, $transaction);
    } catch (Exception $e) {
      // Something else happened, completely unrelated to Stripe
      $this->parsePaymentError($e, $transaction);
    }
    
    $transaction->save();

    return $transaction;
  }
  
  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    $entity = '';
    switch ($model->transactionable_type) {
      case \App\Models\Offer::morphClass:
      case \App\Models\Tenancy::morphClass:
        $entity = "property '".$model->transactionable->property->title."'";
        break;
      default:
        $entity = '';
        break;
    }
    (new \App\Support\Notification($notificationConfig, [
      'toUserId' => $to,
      'fromUserId' => $from,
      'payin_first_name' => isset($model->payin) ? $model->payin->user->first_name : '',
      'payout_first_name' => isset($model->payout) ? $model->payout->user->first_name : '',
      'reason' => $model->payment_error_message,
      'title' => $model->title,
      'entity' => $entity,
      'status' => $model->status,
      'type' => $model->type,
      'description' => $model->description,
      'currency' => $model->currency,
      'amount' => $model->amount,
      'format_amount' => Currency::format($model->amount, strtoupper($model->currency)),
      'messageId' => $model->id,
      'messageType' => Transaction::morphClass,
      'transactionable_id' => $model->transactionable_id,
      'transactionable_type' => $model->transactionable_type,
      'related_id' => $model->transactionable_id,
      'related_type' => $model->transactionable_type,
      'snapshot' => json_encode($model->toArray())
    ] ))->notify();
  }
  
  private function parsePaymentError($e, $transaction)
  {
    $body = $e->getJsonBody();
    $err = $body['error'];
    $transaction->payment_error_message = $e->getMessage();
    $transaction->payment_response = json_encode($body);
    $transaction->status = Transaction::STATUS_FAILED;
    $transaction->payment_error_type = isset($err['type']) ? $err['type']: null;
    $transaction->payment_error_code = isset($err['code']) ? $err['code']: null;
    //$transaction->payment_error_message = isset($err['message']) ? $err['message']: null;
    $transaction->payment_error_status = $e->getHttpStatus();
    $transaction->payment_error_param = isset($err['param']) ? $err['param']: null;
    return $transaction;
  }
  
  private function parsePaymentErrorSetPayout($e, $payout)
  {
    $body = $e->getJsonBody();
    $err = $body['error'];
    $payout->payment_error_message = $e->getMessage();
    $payout->payment_response = json_encode($body);
    $payout->invalid = true;
    $payout->payment_error_type = isset($err['type']) ? $err['type']: null;
    $payout->payment_error_code = isset($err['code']) ? $err['code']: null;
    //$transaction->payment_error_message = isset($err['message']) ? $err['message']: null;
    $payout->payment_error_status = $e->getHttpStatus();
    $payout->payment_error_param = isset($err['param']) ? $err['param']: null;
    return $payout;
  }

}
