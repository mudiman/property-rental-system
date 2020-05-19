<table class="table table-responsive" id="messages-table">
    <thead>
        <th>By User</th>
        <th>Thread Id</th>
        <th>Title</th>
        <th>Message</th>
        <th>Status</th>
        <th>Messageable Id</th>
        <th>Messageable Type</th>
        <th>Snapshot</th>
        <th>Archived</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($messages as $message)
        <tr>
            <td>{!! $message->by_user !!}</td>
            <td>{!! $message->thread_id !!}</td>
            <td>{!! $message->title !!}</td>
            <td>{!! $message->message !!}</td>
            <td>{!! $message->status !!}</td>
            <td>{!! $message->messageable_id !!}</td>
            <td>{!! $message->messageable_type !!}</td>
            <td>{!! $message->snapshot !!}</td>
            <td>{!! $message->archived !!}</td>
            <td>{!! $message->updated_by !!}</td>
            <td>{!! $message->created_by !!}</td>
            <td>{!! $message->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['messages.destroy', $message->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('messages.show', [$message->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('messages.edit', [$message->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>