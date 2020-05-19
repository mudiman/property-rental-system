<table class="table table-responsive" id="agents-table">
    <thead>
        <th>Id</th>
        <th>Agency Id</th>
        <th>User Id</th>
        <th>Status</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($agents as $agent)
        <tr>
            <td>{!! $agent->id !!}</td>
            <td>{!! $agent->agency_id !!}</td>
            <td>{!! $agent->user_id !!}</td>
            <td>{!! $agent->status !!}</td>
            <td>{!! $agent->updated_by !!}</td>
            <td>{!! $agent->created_by !!}</td>
            <td>{!! $agent->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['agents.destroy', $agent->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('agents.show', [$agent->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('agents.edit', [$agent->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>