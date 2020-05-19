<!-- Thread Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thread', 'Thread:') !!}
    {!! Form::text('thread', null, ['class' => 'form-control']) !!}
</div>

<!-- Tenant Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tenant_id', 'Tenant Id:') !!}
    {!! Form::number('tenant_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_id', 'Property Id:') !!}
    {!! Form::number('property_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_id', 'Landlord Id:') !!}
    {!! Form::number('landlord_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Pro Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_pro_id', 'Property Pro Id:') !!}
    {!! Form::number('property_pro_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Previous Offer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('previous_offer_id', 'Previous Offer Id:') !!}
    {!! Form::number('previous_offer_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Payout Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payout_id', 'Payout Id:') !!}
    {!! Form::number('payout_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Payin Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_payin_id', 'Landlord Payin Id:') !!}
    {!! Form::number('landlord_payin_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', $statuses) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Offer Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('offer_expiry', 'Offer Expiry:') !!}
    {!! Form::date('offer_expiry', null, ['class' => 'form-control']) !!}
</div>

<!-- Holding Deposit Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('holding_deposit_expiry', 'Holding Deposit Sign Expiry:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('holding_deposit_expiry', isset($offer->holding_deposit_expiry) ? Carbon\Carbon::parse($offer->holding_deposit_expiry)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>
<!-- Checkin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkin', 'Checkin:') !!}
    <div class='input-group date datepicker'>
        {!! Form::date('checkin', isset($offer->checkin) ? Carbon\Carbon::parse($offer->checkin)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
</div>
</div>

<!-- Checkout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkout', 'Checkout:') !!}
    <div class='input-group date datepicker'>
        {!! Form::date('checkout', isset($offer->checkout) ? Carbon\Carbon::parse($offer->checkout)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
</div>
</div>

<!-- Rent Per Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_month', 'Rent Per Month:') !!}
    {!! Form::number('rent_per_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Rent Per Week Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_week', 'Rent Per Week:') !!}
    {!! Form::number('rent_per_week', null, ['class' => 'form-control']) !!}
</div>

<!-- Rent Per Night Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_night', 'Rent Per Night:') !!}
    {!! Form::number('rent_per_night', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Deposit Week Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_deposit_week', 'Security Deposit Week:') !!}
    {!! Form::number('security_deposit_week', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_deposit_amount', 'Security Deposit Amount:') !!}
    {!! Form::number('security_deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Holding Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_holding_deposit_amount', 'Security Holding Deposit Amount:') !!}
    {!! Form::number('security_holding_deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Special Condition Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('special_condition', 'Special Condition:') !!}
    {!! Form::textarea('special_condition', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('offers.index') !!}" class="btn btn-default">Cancel</a>
</div>
