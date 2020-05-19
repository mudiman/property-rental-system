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

<!-- Offer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('offer_id', 'Offer Id:') !!}
    {!! Form::number('offer_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Thread Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thread', 'Thread:') !!}
    {!! Form::text('thread', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Tenancy Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_tenancy_id', 'Parent Tenancy Id:') !!}
    {!! Form::number('parent_tenancy_id', null, ['class' => 'form-control']) !!}
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

<!-- Mode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mode', 'Mode:') !!}
    {!! Form::text('mode', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>


<!-- Sign Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sign_expiry', 'Sign Expiry:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('sign_expiry', isset($tenancy->sign_expiry) ? Carbon\Carbon::parse($tenancy->sign_expiry)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!--  Checkin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkin', 'Checkin:') !!}
    <div class='input-group date datepicker'>
        {!! Form::datetime('checkin', isset($tenancy->checkin) ? Carbon\Carbon::parse($tenancy->checkin)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
</div>
</div>

<!--  Checkout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkout', 'Checkout:') !!}
    <div class='input-group date datepicker'>
        {!! Form::datetime('checkout', isset($tenancy->checkout) ? Carbon\Carbon::parse($tenancy->checkout)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Actual Checkin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('actual_checkin', 'Actual Checkin:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('actual_checkin', isset($tenancy->actual_checkin) ? Carbon\Carbon::parse($tenancy->actual_checkin)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
</div>
</div>

<!-- Actual Checkout Field -->
<div class="form-group col-sm-6">
    {!! Form::label('actual_checkout', 'Actual Checkout:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('actual_checkout', isset($tenancy->actual_checkout) ? Carbon\Carbon::parse($tenancy->actual_checkout)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Due Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('due_date', 'Due Date:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('due_date', isset($tenancy->due_date) ? Carbon\Carbon::parse($tenancy->due_date)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Due Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('due_amount', 'Due Amount:') !!}
    {!! Form::number('due_amount', null, ['class' => 'form-control']) !!}
</div>
<!-- Tenant Sign Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tenant_sign_location', 'Tenant Sign Location:') !!}
    {!! Form::text('tenant_sign_location', isset($tenancy->tenant_sign_location) ? $tenancy->tenant_sign_location['lat'].",".$tenancy->tenant_sign_location['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Tenant Signed Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tenant_sign_datetime', 'Tenant Signed Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('tenant_sign_datetime', isset($tenancy->tenant_sign_datetime) ? Carbon\Carbon::parse($tenancy->tenant_sign_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Landlord Sign Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_sign_location', 'Landlord Sign Location:') !!}
    {!! Form::text('landlord_sign_location', isset($tenancy->landlord_sign_location) ? $tenancy->landlord_sign_location['lat'].",".$tenancy->landlord_sign_location['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Sign Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_sign_datetime', 'Landlord Sign Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('landlord_sign_datetime', isset($tenancy->landlord_sign_datetime) ? Carbon\Carbon::parse($tenancy->landlord_sign_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
  </div>
</div>

<!-- Special Condition Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('special_condition', 'Special Condition:') !!}
    {!! Form::textarea('special_condition', null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Notice Reminded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_notice_reminded', 'Landlord Notice Reminded:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('landlord_notice_reminded', false) !!}
        {!! Form::checkbox('landlord_notice_reminded', '1', null) !!} 1
    </label>
</div>

<!-- Tenant Notice Reminded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tenant_notice_reminded', 'Tenant Notice Reminded:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('tenant_notice_reminded', false) !!}
        {!! Form::checkbox('tenant_notice_reminded', '1', null) !!} 1
    </label>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tenancies.index') !!}" class="btn btn-default">Cancel</a>
</div>
@if (in_array(App::environment(), ['local', 'dev', 'stage']) && isset($tenancy)) 
<!-- TimeSHift Field -->
<div class="form-group col-sm-12">
    <a href="{!! route('tenancies.timeshift', [$tenancy->id]) !!}" class="btn btn-default">Time Shift</a>
</div>
 @endif