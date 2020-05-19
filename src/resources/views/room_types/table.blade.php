<table class="table table-responsive" id="roomTypes-table">
    <thead>
        <th>id</th>
        <th>Title</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($roomTypes as $roomType)
        <tr>
            <td>{!! $roomType->id !!}</td>
            <td>{!! $roomType->title !!}</td>
            <td>{!! $roomType->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['roomTypes.destroy', $roomType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('roomTypes.show', [$roomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('roomTypes.edit', [$roomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>