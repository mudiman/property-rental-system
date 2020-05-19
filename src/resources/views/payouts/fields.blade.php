<!-- Payment Method Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_method', 'Payment Method:') !!}
    {!! Form::text('payment_method', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Holder Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('holder_name', 'Holder Name:') !!}
    {!! Form::text('holder_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_number', 'Card Number:') !!}
    {!! Form::text('card_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Expire On Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('expire_on_month', 'Expire On Month:') !!}
    {!! Form::number('expire_on_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Expire On Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('expire_on_year', 'Expire On Year:') !!}
    {!! Form::number('expire_on_year', null, ['class' => 'form-control']) !!}
</div>

<!-- Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('expiry', 'Expiry:') !!}
    <div class='input-group date datepicker'>
        {!! Form::date('expiry', isset($payout->expiry) ? Carbon\Carbon::parse($payout->expiry)->format('Y-m-d'): Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>
<!-- Security Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_code', 'Security Code:') !!}
    {!! Form::text('security_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Country Field -->
<div class="form-group col-sm-6">
    {!! Form::label('country', 'Country:') !!}
    {!! Form::text('country', null, ['class' => 'form-control']) !!}
</div>

<!-- Payout Data Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('payout_data', 'Payout Data:') !!}
    {!! Form::textarea('payout_data', null, ['class' => 'form-control']) !!}
</div>

<!-- User Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_reference', 'User Reference:') !!}
    {!! Form::text('user_reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Default Field -->
<div class="form-group col-sm-6">
    {!! Form::label('default', 'Default:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('default', false) !!}
        {!! Form::checkbox('default', '1', null) !!} 1
    </label>
</div>

<!-- Invalid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('invalid', 'Invalid:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('invalid', false) !!}
        {!! Form::checkbox('invalid', '1', null) !!} 1
    </label>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('payouts.index') !!}" class="btn btn-default">Cancel</a>
</div>
