<table class="table table-responsive" id="agencies-table">
    <thead>
        <th>Id</th>
        <th>User Id</th>
        <th>Payin Id</th>
        <th>Name</th>
        <th>Status</th>
        <th>Commission</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($agencies as $agency)
        <tr>
            <td>{!! $agency->id !!}</td>
            <td>{!! $agency->user_id !!}</td>
            <td>{!! $agency->payin_id !!}</td>
            <td>{!! $agency->name !!}</td>
            <td>{!! $agency->status !!}</td>
            <td>{!! $agency->commission !!}</td>
            <td>{!! $agency->updated_by !!}</td>
            <td>{!! $agency->created_by !!}</td>
            <td>{!! $agency->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['agencies.destroy', $agency->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('agencies.show', [$agency->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('agencies.edit', [$agency->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>