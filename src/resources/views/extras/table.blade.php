<table class="table table-responsive" id="extras-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($extras as $extra)
        <tr>
            <td>{!! $extra->id !!}</td>
            <td>{!! $extra->title !!}</td>
            <td>{!! $extra->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['extras.destroy', $extra->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('extras.show', [$extra->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('extras.edit', [$extra->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>