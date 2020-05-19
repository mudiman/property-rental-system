<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $setting->id !!}</p>
</div>

<!-- Setting Field -->
<div class="form-group">
    {!! Form::label('setting', 'Setting:') !!}
    <p>{!! $setting->setting !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $setting->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $setting->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $setting->deleted_by !!}</p>
</div>

