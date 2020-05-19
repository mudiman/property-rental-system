<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Viewed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('viewed', 'Viewed:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('viewed', false) !!}
        {!! Form::checkbox('viewed', '1', null) !!}
    </label>
</div>

<!-- Eventable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eventable_id', 'Eventable Id:') !!}
    {!! Form::number('eventable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Eventable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eventable_type', 'Eventable Type:') !!}
    {!! Form::select('eventable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()))) !!}
</div>

<!-- Start Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_datetime', 'Start Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('start_datetime', isset($event->start_datetime) ? Carbon\Carbon::parse($event->start_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- End Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_datetime', 'End Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('end_datetime', isset($event->end_datetime) ? Carbon\Carbon::parse($event->end_datetime)->toDateTimeString(): null, ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('events.index') !!}" class="btn btn-default">Cancel</a>
</div>
