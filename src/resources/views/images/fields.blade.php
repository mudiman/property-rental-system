<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', 'Path:') !!}
    {!! Form::text('path', null, ['class' => 'form-control']) !!}
</div>

<!-- Bucket Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bucket_name', 'Bucket Name:') !!}
    {!! Form::text('bucket_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Filename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('filename', 'Filename:') !!}
    {!! Form::text('filename', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Mimetype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mimetype', 'Mimetype:') !!}
    {!! Form::text('mimetype', null, ['class' => 'form-control']) !!}
</div>

<!-- Primary Field -->
<div class="form-group col-sm-6">
    {!! Form::label('primary', 'Primary:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('primary', false) !!}
        {!! Form::checkbox('primary', '1', null) !!} 
    </label>
</div>

<!-- Position Field -->
<div class="form-group col-sm-6">
    {!! Form::label('position', 'Position:') !!}
    {!! Form::number('position', null, ['class' => 'form-control']) !!}
</div>

<!-- Imageable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('imageable_id', 'Imageable Id:') !!}
    {!! Form::number('imageable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Imageable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('imageable_type', 'Imageable Type:') !!}
    {!! Form::text('imageable_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('images.index') !!}" class="btn btn-default">Cancel</a>
</div>
