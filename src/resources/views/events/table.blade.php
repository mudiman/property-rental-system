<table class="table table-responsive" id="events-table">
    <thead>
        <th>Title</th>
        <th>Description</th>
        <th>User Id</th>
        <th>Viewed</th>
        <th>Eventable Id</th>
        <th>Eventable Type</th>
        <th>Start Datetime</th>
        <th>End Datetime</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{!! $event->title !!}</td>
            <td>{!! $event->description !!}</td>
            <td>{!! $event->user_id !!}</td>
            <td>{!! $event->viewed !!}</td>
            <td>{!! $event->eventable_id !!}</td>
            <td>{!! $event->eventable_type !!}</td>
            <td>{!! $event->start_datetime !!}</td>
            <td>{!! $event->end_datetime !!}</td>
            <td>
                {!! Form::open(['route' => ['events.destroy', $event->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('events.show', [$event->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('events.edit', [$event->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>