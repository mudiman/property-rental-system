<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $event->id !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $event->title !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $event->description !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $event->user_id !!}</p>
</div>

<!-- Viewed Field -->
<div class="form-group">
    {!! Form::label('viewed', 'Viewed:') !!}
    <p>{!! $event->viewed !!}</p>
</div>

<!-- Eventable Id Field -->
<div class="form-group">
    {!! Form::label('eventable_id', 'Eventable Id:') !!}
    <p>{!! $event->eventable_id !!}</p>
</div>

<!-- Eventable Type Field -->
<div class="form-group">
    {!! Form::label('eventable_type', 'Eventable Type:') !!}
    <p>{!! $event->eventable_type !!}</p>
</div>

<!-- Start Datetime Field -->
<div class="form-group">
    {!! Form::label('start_datetime', 'Start Datetime:') !!}
    <p>{!! $event->start_datetime !!}</p>
</div>

<!-- End Datetime Field -->
<div class="form-group">
    {!! Form::label('end_datetime', 'End Datetime:') !!}
    <p>{!! $event->end_datetime !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $event->deleted_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $event->updated_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $event->created_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $event->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $event->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $event->deleted_by !!}</p>
</div>