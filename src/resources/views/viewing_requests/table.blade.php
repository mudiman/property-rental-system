<table class="table table-responsive" id="viewingRequests-table">
    <thead>
        <th>Viewing Id</th>
        <th>View By User</th>
        <th>Checkin</th>
        <th>Status</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($viewingRequests as $viewingRequest)
        <tr>
            <td>{!! $viewingRequest->viewing_id !!}</td>
            <td>{!! $viewingRequest->view_by_user !!}</td>
            <td>{!! $viewingRequest->checkin !!}</td>
            <td>{!! $viewingRequest->status !!}</td>
            <td>{!! $viewingRequest->updated_by !!}</td>
            <td>{!! $viewingRequest->created_by !!}</td>
            <td>{!! $viewingRequest->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['viewingRequests.destroy', $viewingRequest->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('viewingRequests.show', [$viewingRequest->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('viewingRequests.edit', [$viewingRequest->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>