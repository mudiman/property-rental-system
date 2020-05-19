<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $propertyPro->id !!}</p>
</div>

<!-- Thread Field -->
<div class="form-group">
    {!! Form::label('thread', 'Thread:') !!}
    <p>{!! $propertyPro->thread !!}</p>
</div>

<!-- Landlord Id Field -->
<div class="form-group">
    {!! Form::label('landlord_id', 'Landlord Id:') !!}
    <p>{!! $propertyPro->landlord_id !!}</p>
</div>

<!-- Property Pro Id Field -->
<div class="form-group">
    {!! Form::label('property_pro_id', 'Property Pro Id:') !!}
    <p>{!! $propertyPro->property_pro_id !!}</p>
</div>

<!-- Property Id Field -->
<div class="form-group">
    {!! Form::label('property_id', 'Property Id:') !!}
    <p>{!! $propertyPro->property_id !!}</p>
</div>

<!-- Property Pro Payin Id Field -->
<div class="form-group">
    {!! Form::label('property_pro_payin_id', 'Property Pro Payin Id:') !!}
    <p>{!! $propertyPro->property_pro_payin_id !!}</p>
</div>

<!-- Property Pro Sign Location Field -->
<div class="form-group">
    {!! Form::label('property_pro_sign_location', 'Property Pro Sign Location:') !!}
    <p>{!! isset($propertyPro->property_pro_sign_location) ? $propertyPro->property_pro_sign_location['lat'].",".$propertyPro->property_pro_sign_location['lon']: null !!}</p>
</div>

<!-- Property Pro Sign Datetime Field -->
<div class="form-group">
    {!! Form::label('property_pro_sign_datetime', 'Property Pro Sign Datetime:') !!}
    <p>{!! $propertyPro->property_pro_sign_datetime !!}</p>
</div>

<!-- Landlord Sign Location Field -->
<div class="form-group">
    {!! Form::label('landlord_sign_location', 'Landlord Sign Location:') !!}
    <p>{!! isset($propertyPro->landlord_sign_location) ? $propertyPro->landlord_sign_location['lat'].",".$propertyPro->landlord_sign_location['lon']: null !!}</p>
</div>

<!-- Landlord Sign Datetime Field -->
<div class="form-group">
    {!! Form::label('landlord_sign_datetime', 'Landlord Sign Datetime:') !!}
    <p>{!! $propertyPro->landlord_sign_datetime !!}</p>
</div>

<!-- Price Type Field -->
<div class="form-group">
    {!! Form::label('price_type', 'Price Type:') !!}
    <p>{!! $propertyPro->price_type !!}</p>
</div>

<!-- Price Field -->
<div class="form-group">
    {!! Form::label('price', 'Price:') !!}
    <p>{!! $propertyPro->price !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $propertyPro->status !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $propertyPro->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $propertyPro->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $propertyPro->updated_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $propertyPro->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $propertyPro->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $propertyPro->deleted_by !!}</p>
</div>

