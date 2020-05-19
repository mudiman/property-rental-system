<table class="table table-responsive" id="propertyRoomTypes-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Icon</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($propertyRoomTypes as $propertyRoomType)
        <tr>
            <td>{!! $propertyRoomType->id !!}</td>
            <td>{!! $propertyRoomType->title !!}</td>
            <td>{!! $propertyRoomType->icon !!}</td>
            <td>{!! $propertyRoomType->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['propertyRoomTypes.destroy', $propertyRoomType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('propertyRoomTypes.show', [$propertyRoomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('propertyRoomTypes.edit', [$propertyRoomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>