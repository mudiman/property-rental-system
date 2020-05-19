<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $tenancy->id !!}</p>
</div>

<!-- Tenant Id Field -->
<div class="form-group">
    {!! Form::label('tenant_id', 'Tenant Id:') !!}
    <p>{!! $tenancy->tenant_id !!}</p>
</div>

<!-- Property Id Field -->
<div class="form-group">
    {!! Form::label('property_id', 'Property Id:') !!}
    <p>{!! $tenancy->property_id !!}</p>
</div>

<!-- Offer Id Field -->
<div class="form-group">
    {!! Form::label('offer_id', 'Offer Id:') !!}
    <p>{!! $tenancy->offer_id !!}</p>
</div>

<!-- Thread Field -->
<div class="form-group">
    {!! Form::label('thread', 'Thread:') !!}
    <p>{!! $tenancy->thread !!}</p>
</div>

<!-- Parent Tenancy Id Field -->
<div class="form-group">
    {!! Form::label('parent_tenancy_id', 'Parent Tenancy Id:') !!}
    <p>{!! $tenancy->parent_tenancy_id !!}</p>
</div>

<!-- Landlord Id Field -->
<div class="form-group">
    {!! Form::label('landlord_id', 'Landlord Id:') !!}
    <p>{!! $tenancy->landlord_id !!}</p>
</div>

<!-- Property Pro Id Field -->
<div class="form-group">
    {!! Form::label('property_pro_id', 'Property Pro Id:') !!}
    <p>{!! $tenancy->property_pro_id !!}</p>
</div>

<!-- Payout Id Field -->
<div class="form-group">
    {!! Form::label('payout_id', 'Payout Id:') !!}
    <p>{!! $tenancy->payout_id !!}</p>
</div>

<!-- Landlord Payin Id Field -->
<div class="form-group">
    {!! Form::label('landlord_payin_id', 'Landlord Payin Id:') !!}
    <p>{!! $tenancy->landlord_payin_id !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $tenancy->status !!}</p>
</div>

<!-- Mode Field -->
<div class="form-group">
    {!! Form::label('mode', 'Mode:') !!}
    <p>{!! $tenancy->mode !!}</p>
</div>

<!-- Previous Status Field -->
<div class="form-group">
    {!! Form::label('previous_status', 'Previous Status:') !!}
    <p>{!! $tenancy->previous_status !!}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{!! $tenancy->type !!}</p>
</div>

<!-- Sign Expiry Field -->
<div class="form-group">
    {!! Form::label('sign_expiry', 'Sign Expiry:') !!}
    <p>{!! $tenancy->sign_expiry !!}</p>
</div>

<!-- Checkin Field -->
<div class="form-group">
    {!! Form::label('checkin', 'Checkin:') !!}
    <p>{!! $tenancy->checkin !!}</p>
</div>

<!-- Checkout Field -->
<div class="form-group">
    {!! Form::label('checkout', 'Checkout:') !!}
    <p>{!! $tenancy->checkout !!}</p>
</div>

<!-- Actual Checkin Field -->
<div class="form-group">
    {!! Form::label('actual_checkin', 'Actual Checkin:') !!}
    <p>{!! $tenancy->actual_checkin !!}</p>
</div>

<!-- Actual Checkout Field -->
<div class="form-group">
    {!! Form::label('actual_checkout', 'Actual Checkout:') !!}
    <p>{!! $tenancy->actual_checkout !!}</p>
</div>

<!-- Due Date Field -->
<div class="form-group">
    {!! Form::label('due_date', 'Due Date:') !!}
    <p>{!! $tenancy->due_date !!}</p>
</div>

<!-- Due Amount Field -->
<div class="form-group">
    {!! Form::label('due_amount', 'Due Amount:') !!}
    <p>{!! $tenancy->due_amount !!}</p>
</div>

<!-- Tenant Sign Location Field -->
<div class="form-group">
    {!! Form::label('tenant_sign_location', 'Tenant Sign Location:') !!}
    <p>{!! isset($tenancy->tenant_sign_location) ? $tenancy->tenant_sign_location['lat'].",".$tenancy->tenant_sign_location['lon']: null !!}</p>
</div>

<!-- Tenant Sign Datetime Field -->
<div class="form-group">
    {!! Form::label('tenant_sign_datetime', 'Tenant Sign Datetime:') !!}
    <p>{!! $tenancy->tenant_sign_datetime !!}</p>
</div>

<!-- Landlord Sign Location Field -->
<div class="form-group">
    {!! Form::label('landlord_sign_location', 'Landlord Sign Location:') !!}
    <p>{!! isset($tenancy->landlord_sign_location) ? $tenancy->landlord_sign_location['lat'].",".$tenancy->landlord_sign_location['lon']: null !!}</p>
</div>

<!-- Landlord Sign Datetime Field -->
<div class="form-group">
    {!! Form::label('landlord_sign_datetime', 'Landlord Sign Datetime:') !!}
    <p>{!! $tenancy->landlord_sign_datetime !!}</p>
</div>

<!-- Special Condition Field -->
<div class="form-group">
    {!! Form::label('special_condition', 'Special Condition:') !!}
    <p>{!! $tenancy->special_condition !!}</p>
</div>

<!-- Landlord Notice Reminded Field -->
<div class="form-group">
    {!! Form::label('landlord_notice_reminded', 'Landlord Notice Reminded:') !!}
    <p>{!! $tenancy->landlord_notice_reminded !!}</p>
</div>

<!-- Tenant Notice Reminded Field -->
<div class="form-group">
    {!! Form::label('tenant_notice_reminded', 'Tenant Notice Reminded:') !!}
    <p>{!! $tenancy->tenant_notice_reminded !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $tenancy->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $tenancy->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $tenancy->updated_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $tenancy->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $tenancy->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $tenancy->deleted_by !!}</p>
</div>

