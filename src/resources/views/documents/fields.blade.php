<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
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

<!-- Issuing Country Field -->
<div class="form-group col-sm-6">
    {!! Form::label('issuing_country', 'Issuing Country:') !!}
    {!! Form::text('issuing_country', null, ['class' => 'form-control']) !!}
</div>

<!-- Verified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('verified', 'Verified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('verified', false) !!}
        {!! Form::checkbox('verified', '1', null) !!}
    </label>
</div>

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

<!-- File Front Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_front_path', 'File Front Path:') !!}
    {!! Form::text('file_front_path', null, ['class' => 'form-control']) !!}
</div>

<!-- File Front Filename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_front_filename', 'File Front Filename:') !!}
    {!! Form::text('file_front_filename', null, ['class' => 'form-control']) !!}
</div>

<!-- File Front Mimetype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_front_mimetype', 'File Front Mimetype:') !!}
    {!! Form::text('file_front_mimetype', null, ['class' => 'form-control']) !!}
</div>

<!-- File Back Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_back_path', 'File Back Path:') !!}
    {!! Form::text('file_back_path', null, ['class' => 'form-control']) !!}
</div>

<!-- File Back Filename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_back_filename', 'File Back Filename:') !!}
    {!! Form::text('file_back_filename', null, ['class' => 'form-control']) !!}
</div>

<!-- File Back Mimetype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_back_mimetype', 'File Back Mimetype:') !!}
    {!! Form::text('file_back_mimetype', null, ['class' => 'form-control']) !!}
</div>

<!-- Documentable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documentable_id', 'Documentable Id:') !!}
    {!! Form::number('documentable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Documentable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documentable_type', 'Documentable Type:') !!}
    {!! Form::text('documentable_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('documents.index') !!}" class="btn btn-default">Cancel</a>
</div>
