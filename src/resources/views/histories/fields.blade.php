<!-- Historiable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('historiable_id', 'Historiable Id:') !!}
    {!! Form::number('historiable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Historiable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('historiable_type', 'Historiable Type:') !!}
    {!! Form::text('historiable_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Snapshot Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('snapshot', 'Snapshot:') !!}
    {!! Form::textarea('snapshot', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('histories.index') !!}" class="btn btn-default">Cancel</a>
</div>
