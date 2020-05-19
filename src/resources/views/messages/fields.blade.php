<!-- By User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('by_user', 'By User:') !!}
    {!! Form::number('by_user', isset($message->by_user) ? $message->by_user: Auth::user()->id, ['class' => 'form-control']) !!}
</div>

<!-- Thread Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thread_id', 'Thread Id:') !!}
    {!! Form::number('thread_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-6">
    {!! Form::label('message', 'Message:') !!}
    {!! Form::text('message', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Messageable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('messageable_id', 'Messageable Id:') !!}
    {!! Form::number('messageable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Messageable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('messageable_type', 'Messageable Type:') !!}
    {!! Form::select('messageable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap())))  !!}
</div>

<!-- Snapshot Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('snapshot', 'Snapshot:') !!}
    {!! Form::textarea('snapshot', null, ['class' => 'form-control']) !!}
</div>

<!-- Archived Field -->
<div class="form-group col-sm-6">
    {!! Form::label('archived', 'Archived:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('archived', false) !!}
        {!! Form::checkbox('archived', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('messages.index') !!}" class="btn btn-default">Cancel</a>
</div>
