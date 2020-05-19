<table class="table table-responsive" id="viewings-table">
    <thead>
        <th>Property Id</th>
        <th>Conducted By</th>
        <th>Start Datetime</th>
        <th>End Datetime</th>
        <th>Type</th>
        <th>Status</th>
        <th>Checkin</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($viewings as $viewing)
        <tr>
            <td>{!! $viewing->property_id !!}</td>
            <td>{!! $viewing->conducted_by !!}</td>
            <td>{!! $viewing->start_datetime !!}</td>
            <td>{!! $viewing->end_datetime !!}</td>
            <td>{!! $viewing->type !!}</td>
            <td>{!! $viewing->status !!}</td>
            <td>{!! $viewing->checkin !!}</td>
            <td>{!! $viewing->updated_by !!}</td>
            <td>{!! $viewing->created_by !!}</td>
            <td>{!! $viewing->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['viewings.destroy', $viewing->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('viewings.show', [$viewing->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('viewings.edit', [$viewing->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>