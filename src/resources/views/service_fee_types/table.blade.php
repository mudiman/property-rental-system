<table class="table table-responsive" id="serviceFeeTypes-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($serviceFeeTypes as $serviceFeeType)
        <tr>
            <td>{!! $serviceFeeType->id !!}</td>
            <td>{!! $serviceFeeType->title !!}</td>
            <td>{!! $serviceFeeType->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['serviceFeeTypes.destroy', $serviceFeeType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('serviceFeeTypes.show', [$serviceFeeType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('serviceFeeTypes.edit', [$serviceFeeType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>