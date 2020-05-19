<!-- Property Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_id', 'Property Id:') !!}
    {!! Form::number('property_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Conducted By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('conducted_by', 'Conducted By:') !!}
    {!! Form::number('conducted_by', null, ['class' => 'form-control']) !!}
</div>

<!-- Start Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_datetime', 'Start Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('start_datetime', isset($viewing->start_datetime) ? Carbon\Carbon::parse($viewing->start_datetime)->toDateTimeString(): Carbon\Carbon::now()->toDateTimeString(), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- End Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_datetime', 'End Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('end_datetime', isset($viewing->end_datetime) ? Carbon\Carbon::parse($viewing->end_datetime)->toDateTimeString(): Carbon\Carbon::now()->toDateTimeString(), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Checkin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('checkin', 'Checkin:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('checkin', false) !!}
        {!! Form::checkbox('checkin', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('viewings.index') !!}" class="btn btn-default">Cancel</a>
</div>
