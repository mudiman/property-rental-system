<!-- Setting Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('setting', 'Setting:') !!}
    {!! Form::textarea('setting', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('settings.edit', 1) !!}" class="btn btn-default">Cancel</a>
</div>
