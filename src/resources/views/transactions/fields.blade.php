<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Payin Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payin_id', 'Payin Id:') !!}
    {!! Form::number('payin_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Payout Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payout_id', 'Payout Id:') !!}
    {!! Form::number('payout_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Transaction Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_transaction_id', 'Parent Transaction Id:') !!}
    {!! Form::number('parent_transaction_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Transactionable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transactionable_id', 'Transactionable Id:') !!}
    {!! Form::number('transactionable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Transactionable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transactionable_type', 'Transactionable Type:') !!}
    {!! Form::select('transactionable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap())))  !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::select('title', [
    \App\Models\Transaction::TITLE_INITIAL_DEPOSIT => \App\Models\Transaction::TITLE_INITIAL_DEPOSIT, 
    \App\Models\Transaction::TITLE_FIRST_RENT => \App\Models\Transaction::TITLE_FIRST_RENT, 
    \App\Models\Transaction::TITLE_MONTHLY_RENT => \App\Models\Transaction::TITLE_MONTHLY_RENT, 
    \App\Models\Transaction::TITLE_LANDLORD_SECURITY_DEPOSIT => \App\Models\Transaction::TITLE_LANDLORD_SECURITY_DEPOSIT, 
    \App\Models\Transaction::TITLE_DEBIT_MONTHLY_RENT => \App\Models\Transaction::TITLE_DEBIT_MONTHLY_RENT, 
    ])  !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type', [ 
    \App\Models\Transaction::TYPE_CREDIT => \App\Models\Transaction::TYPE_CREDIT, 
    \App\Models\Transaction::TYPE_DEBIT => \App\Models\Transaction::TYPE_DEBIT, 
    ])  !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control']) !!}
</div>

<!-- Smoor Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smoor_commission', 'Smoor Commission:') !!}
    {!! Form::number('smoor_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Payment Gateway Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_gateway_commission', 'Payment Gateway Commission:') !!}
    {!! Form::number('payment_gateway_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_commission', 'Landlord Commission:') !!}
    {!! Form::number('landlord_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Agency Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agency_commission', 'Agency Commission:') !!}
    {!! Form::number('agency_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Pro Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_pro_commission', 'Property Pro Commission:') !!}
    {!! Form::number('property_pro_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [ 
    \App\Models\Transaction::STATUS_NONE => \App\Models\Transaction::STATUS_NONE,
    \App\Models\Transaction::STATUS_START => \App\Models\Transaction::STATUS_START, 
    \App\Models\Transaction::STATUS_FAILED => \App\Models\Transaction::STATUS_FAILED, 
    \App\Models\Transaction::STATUS_DONE => \App\Models\Transaction::STATUS_DONE, 
    \App\Models\Transaction::STATUS_REFUND => \App\Models\Transaction::STATUS_REFUND, 
    \App\Models\Transaction::STATUS_ABORTED => \App\Models\Transaction::STATUS_ABORTED, 
    ])  !!}
</div>

<!-- Transaction Data Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('transaction_data', 'Transaction Data:') !!}
    {!! Form::textarea('transaction_data', null, ['class' => 'form-control']) !!}
</div>

<!-- Transaction Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transaction_reference', 'Transaction Reference:') !!}
    {!! Form::text('transaction_reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Smoor Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smoor_reference', 'Smoor Reference:') !!}
    {!! Form::text('smoor_reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Dividen Done Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dividen_done', 'Dividen Done:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('dividen_done', false) !!}
        {!! Form::checkbox('dividen_done', '1', null) !!}
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('transactions.index') !!}" class="btn btn-default">Cancel</a>
</div>
