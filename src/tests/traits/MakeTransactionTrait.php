<?php

use Faker\Factory as Faker;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;

trait MakeTransactionTrait
{
    /**
     * Create fake instance of Transaction and save it in database
     *
     * @param array $transactionFields
     * @return Transaction
     */
    public function makeTransaction($transactionFields = [])
    {
        /** @var TransactionRepository $transactionRepo */
        $transactionRepo = App::make(TransactionRepository::class);
        $theme = $this->fakeTransactionData($transactionFields);
        return $transactionRepo->create($theme);
    }

    /**
     * Get fake instance of Transaction
     *
     * @param array $transactionFields
     * @return Transaction
     */
    public function fakeTransaction($transactionFields = [])
    {
        return new Transaction($this->fakeTransactionData($transactionFields));
    }

    /**
     * Get fake data of Transaction
     *
     * @param array $postFields
     * @return array
     */
    public function fakeTransactionData($transactionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'payin_id' => $fake->randomDigitNotNull,
            'payout_id' => $fake->randomDigitNotNull,
            'parent_transaction_id' => $fake->randomDigitNotNull,
            'transactionable_id' => $fake->randomDigitNotNull,
            'transactionable_type' => $fake->word,
            'title' => $fake->word,
            'description' => $fake->word,
            'type' => $fake->word,
            'amount' => $fake->randomDigitNotNull,
            'currency' => $fake->word,
            'smoor_commission' => $fake->randomDigitNotNull,
            'payment_gateway_commission' => $fake->randomDigitNotNull,
            'landlord_commission' => $fake->randomDigitNotNull,
            'agency_commission' => $fake->randomDigitNotNull,
            'property_pro_commission' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'transaction_data' => $fake->text,
            'transaction_reference' => $fake->word,
            'smoor_reference' => $fake->word,
            'indempotent_key' => $fake->word,
            'dividen_done' => $fake->word,
            'payment_error_message' => $fake->text,
            'payment_error_type' => $fake->word,
            'payment_error_code' => $fake->word,
            'payment_error_status' => $fake->word,
            'payment_error_param' => $fake->word,
            'payment_response' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->randomDigitNotNull,
            'created_by' => $fake->randomDigitNotNull,
            'deleted_by' => $fake->randomDigitNotNull
        ], $transactionFields);
    }
}
