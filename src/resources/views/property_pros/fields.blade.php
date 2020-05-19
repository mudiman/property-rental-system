<!-- Thread Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thread', 'Thread:') !!}
    {!! Form::text('thread', null, ['class' => 'form-control']) !!}
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

<!-- Property Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_id', 'Property Id:') !!}
    {!! Form::number('property_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Pro Payin Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_pro_payin_id', 'Property Pro Payin Id:') !!}
    {!! Form::number('property_pro_payin_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Pro Sign Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_pro_sign_location', 'Property Pro Sign Location:') !!}
    {!! Form::text('property_pro_sign_location', isset($tenancy->property_pro_sign_location) ? $tenancy->property_pro_sign_location['lat'].",".$tenancy->property_pro_sign_location['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Property Pro Sign Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_pro_sign_datetime', 'Property Pro Sign Datetime:') !!}
    {!! Form::datetime('property_pro_sign_datetime', isset($tenancy->property_pro_sign_datetime) ? Carbon\Carbon::parse($tenancy->property_pro_sign_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Sign Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_sign_location', 'Landlord Sign Location:') !!}
    {!! Form::text('landlord_sign_location', isset($tenancy->landlord_sign_location) ? $tenancy->landlord_sign_location['lat'].",".$tenancy->landlord_sign_location['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Landlord Sign Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_sign_datetime', 'Landlord Sign Datetime:') !!}
    {!! Form::datetime('landlord_sign_datetime', isset($tenancy->landlord_sign_datetime) ? Carbon\Carbon::parse($tenancy->landlord_sign_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
</div>

<!-- Price Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price_type', 'Price Type:') !!}
    {!! Form::text('price_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', isset($propertyPro->status) ? $propertyPro->status: 'request', ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('propertyPros.index') !!}" class="btn btn-default">Cancel</a>
</div>
