<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $report->id !!}</p>
</div>

<!-- By User Field -->
<div class="form-group">
    {!! Form::label('by_user', 'By User:') !!}
    <p>{!! $report->by_user !!}</p>
</div>

<!-- Comment Field -->
<div class="form-group">
    {!! Form::label('comment', 'Comment:') !!}
    <p>{!! $report->comment !!}</p>
</div>

<!-- Reportable Id Field -->
<div class="form-group">
    {!! Form::label('reportable_id', 'Reportable Id:') !!}
    <p>{!! $report->reportable_id !!}</p>
</div>

<!-- Reportable Type Field -->
<div class="form-group">
    {!! Form::label('reportable_type', 'Reportable Type:') !!}
    <p>{!! $report->reportable_type !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $report->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $report->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $report->updated_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $report->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $report->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $report->deleted_by !!}</p>
</div>