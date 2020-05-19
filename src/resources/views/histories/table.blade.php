<table class="table table-responsive" id="histories-table">
    <thead>
        <th>Historiable Id</th>
        <th>Historiable Type</th>
        <th>Snapshot</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($histories as $history)
        <tr>
            <td>{!! $history->historiable_id !!}</td>
            <td>{!! $history->historiable_type !!}</td>
            <td>{!! $history->snapshot !!}</td>
            <td>{!! $history->updated_by !!}</td>
            <td>{!! $history->created_by !!}</td>
            <td>{!! $history->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['histories.destroy', $history->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('histories.show', [$history->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('histories.edit', [$history->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>