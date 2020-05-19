<table class="table table-responsive" id="devices-table">
    <thead>
        <th>Api Version</th>
        <th>User Id</th>
        <th>token_id</th>
        <th>Type</th>
        <th>Device Id</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($devices as $device)
        <tr>
            <td>{!! $device->api_version !!}</td>
            <td>{!! $device->user_id !!}</td>
            <td>{!! $device->token_id !!}</td>
            <td>{!! $device->type !!}</td>
            <td>{!! $device->device_id !!}</td>
            <td>{!! $device->updated_by !!}</td>
            <td>{!! $device->created_by !!}</td>
            <td>{!! $device->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['devices.destroy', $device->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('devices.show', [$device->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('devices.edit', [$device->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>