<table class="table table-responsive" id="threads-table">
    <thead>
        <th>Title</th>
        <th>Status</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($threads as $thread)
        <tr>
            <td>{!! $thread->title !!}</td>
            <td>{!! $thread->status !!}</td>
            <td>
                {!! Form::open(['route' => ['threads.destroy', $thread->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('threads.show', [$thread->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('threads.edit', [$thread->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>