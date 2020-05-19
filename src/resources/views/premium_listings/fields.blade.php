<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_id', 'Property Id:') !!}
    {!! Form::number('property_id', null, ['class' => 'form-control']) !!}
</div>

<!-- End Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_datetime', 'End Datetime:') !!}
    <div class='input-group date datetimepicker'>
        {!! Form::datetime('end_datetime', isset($premiumListing->end_datetime) ? Carbon\Carbon::parse($premiumListing->end_datetime)->toDateTimeString(): Carbon\Carbon::now()->toDateTimeString(), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('premiumListings.index') !!}" class="btn btn-default">Cancel</a>
</div>
