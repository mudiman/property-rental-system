<table class="table table-responsive" id="lettingTypes-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Icon</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($lettingTypes as $lettingType)
        <tr>
            <td>{!! $lettingType->id !!}</td>
            <td>{!! $lettingType->title !!}</td>
            <td>{!! $lettingType->icon !!}</td>
            <td>{!! $lettingType->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['lettingTypes.destroy', $lettingType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('lettingTypes.show', [$lettingType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('lettingTypes.edit', [$lettingType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>