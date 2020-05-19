<!-- Viewing Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('viewing_id', 'Viewing Id:') !!}
    {!! Form::number('viewing_id', null, ['class' => 'form-control']) !!}
</div>

<!-- View By User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('view_by_user', 'View By User:') !!}
    {!! Form::number('view_by_user', null, ['class' => 'form-control']) !!}
</div>

<!-- Checkin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkin', 'Checkin:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('checkin', false) !!}
        {!! Form::checkbox('checkin', '1', null) !!} 1
    </label>
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', isset($viewingRequest->status) ? $viewingRequest->status: 'request', ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('viewingRequests.index') !!}" class="btn btn-default">Cancel</a>
</div>
