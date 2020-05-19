<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $transaction->id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $transaction->user_id !!}</p>
</div>

<!-- Payin Id Field -->
<div class="form-group">
    {!! Form::label('payin_id', 'Payin Id:') !!}
    <p>{!! $transaction->payin_id !!}</p>
</div>

<!-- Payout Id Field -->
<div class="form-group">
    {!! Form::label('payout_id', 'Payout Id:') !!}
    <p>{!! $transaction->payout_id !!}</p>
</div>

<!-- Parent Transaction Id Field -->
<div class="form-group">
    {!! Form::label('parent_transaction_id', 'Parent Transaction Id:') !!}
    <p>{!! $transaction->parent_transaction_id !!}</p>
</div>

<!-- Transactionable Id Field -->
<div class="form-group">
    {!! Form::label('transactionable_id', 'Transactionable Id:') !!}
    <p>{!! $transaction->transactionable_id !!}</p>
</div>

<!-- Transactionable Type Field -->
<div class="form-group">
    {!! Form::label('transactionable_type', 'Transactionable Type:') !!}
    <p>{!! $transaction->transactionable_type !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $transaction->title !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $transaction->description !!}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{!! $transaction->type !!}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('amount', 'Amount:') !!}
    <p>{!! $transaction->amount !!}</p>
</div>

<!-- Currency Field -->
<div class="form-group">
    {!! Form::label('currency', 'Currency:') !!}
    <p>{!! $transaction->currency !!}</p>
</div>

<!-- Smoor Commission Field -->
<div class="form-group">
    {!! Form::label('smoor_commission', 'Smoor Commission:') !!}
    <p>{!! $transaction->smoor_commission !!}</p>
</div>

<!-- Payment Gateway Commission Field -->
<div class="form-group">
    {!! Form::label('payment_gateway_commission', 'Payment Gateway Commission:') !!}
    <p>{!! $transaction->payment_gateway_commission !!}</p>
</div>

<!-- Landlord Commission Field -->
<div class="form-group">
    {!! Form::label('landlord_commission', 'Landlord Commission:') !!}
    <p>{!! $transaction->landlord_commission !!}</p>
</div>

<!-- Agency Commission Field -->
<div class="form-group">
    {!! Form::label('agency_commission', 'Agency Commission:') !!}
    <p>{!! $transaction->agency_commission !!}</p>
</div>

<!-- Property Pro Commission Field -->
<div class="form-group">
    {!! Form::label('property_pro_commission', 'Property Pro Commission:') !!}
    <p>{!! $transaction->property_pro_commission !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $transaction->status !!}</p>
</div>

<!-- Transaction Data Field -->
<div class="form-group">
    {!! Form::label('transaction_data', 'Transaction Data:') !!}
    <p>{!! $transaction->transaction_data !!}</p>
</div>

<!-- Transaction Reference Field -->
<div class="form-group">
    {!! Form::label('transaction_reference', 'Transaction Reference:') !!}
    <p>{!! $transaction->transaction_reference !!}</p>
</div>

<!-- Smoor Reference Field -->
<div class="form-group">
    {!! Form::label('smoor_reference', 'Smoor Reference:') !!}
    <p>{!! $transaction->smoor_reference !!}</p>
</div>

<!-- Indempotent Key Field -->
<div class="form-group">
    {!! Form::label('indempotent_key', 'Indempotent Key:') !!}
    <p>{!! $transaction->indempotent_key !!}</p>
</div>

<!-- Dividen Done Field -->
<div class="form-group">
    {!! Form::label('dividen_done', 'Dividen Done:') !!}
    <p>{!! $transaction->dividen_done !!}</p>
</div>

<!-- Payment Error Message Field -->
<div class="form-group">
    {!! Form::label('payment_error_message', 'Payment Error Message:') !!}
    <p>{!! $transaction->payment_error_message !!}</p>
</div>

<!-- Payment Error Type Field -->
<div class="form-group">
    {!! Form::label('payment_error_type', 'Payment Error Type:') !!}
    <p>{!! $transaction->payment_error_type !!}</p>
</div>

<!-- Payment Error Code Field -->
<div class="form-group">
    {!! Form::label('payment_error_code', 'Payment Error Code:') !!}
    <p>{!! $transaction->payment_error_code !!}</p>
</div>

<!-- Payment Error Status Field -->
<div class="form-group">
    {!! Form::label('payment_error_status', 'Payment Error Status:') !!}
    <p>{!! $transaction->payment_error_status !!}</p>
</div>

<!-- Payment Error Param Field -->
<div class="form-group">
    {!! Form::label('payment_error_param', 'Payment Error Param:') !!}
    <p>{!! $transaction->payment_error_param !!}</p>
</div>

<!-- Payment Response Field -->
<div class="form-group">
    {!! Form::label('payment_response', 'Payment Response:') !!}
    <p>{!! $transaction->payment_response !!}</p>
</div>

<!-- Refund Status Field -->
<div class="form-group">
    {!! Form::label('refund_status', 'Refund Status:') !!}
    <p>{!! $transaction->refund_status !!}</p>
</div>

<!-- Refund Reference Field -->
<div class="form-group">
    {!! Form::label('refund_reference', 'Refund Reference:') !!}
    <p>{!! $transaction->refund_reference !!}</p>
</div>

<!-- Refund Response Field -->
<div class="form-group">
    {!! Form::label('refund_response', 'Refund Response:') !!}
    <p>{!! $transaction->refund_response !!}</p>
</div>

<!-- Retries Field -->
<div class="form-group">
    {!! Form::label('retries', 'Retries:') !!}
    <p>{!! $transaction->retries !!}</p>
</div>

<!-- Token Used Field -->
<div class="form-group">
    {!! Form::label('token_used', 'Token Used:') !!}
    <p>{!! $transaction->token_used !!}</p>
</div>

<!-- Due Date Field -->
<div class="form-group">
    {!! Form::label('due_date', 'Due Date:') !!}
    <p>{!! $transaction->due_date !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $transaction->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $transaction->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $transaction->updated_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $transaction->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $transaction->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $transaction->deleted_by !!}</p>
</div>

