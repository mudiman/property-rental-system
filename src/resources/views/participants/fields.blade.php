<!-- Thread Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thread_id', 'Thread Id:') !!}
    {!! Form::number('thread_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('participants.index') !!}" class="btn btn-default">Cancel</a>
</div>
