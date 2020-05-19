<!-- Property Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_id', 'Property Id:') !!}
    {!! Form::number('property_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- View Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('view_type', 'View Type:') !!}
    {!! Form::text('view_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Viewed Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('viewed_datetime', 'Viewed Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('viewed_datetime', isset($statistic->viewed_datetime) ? Carbon\Carbon::parse($statistic->viewed_datetime)->toDateTimeString(): Carbon\Carbon::now()->toDateTimeString(), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('statistics.index') !!}" class="btn btn-default">Cancel</a>
</div>
