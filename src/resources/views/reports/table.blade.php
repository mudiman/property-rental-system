<table class="table table-responsive" id="reports-table">
    <thead>
        <th>By User</th>
        <th>Comment</th>
        <th>Reportable Id</th>
        <th>Reportable Type</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{!! $report->by_user !!}</td>
            <td>{!! $report->comment !!}</td>
            <td>{!! $report->reportable_id !!}</td>
            <td>{!! $report->reportable_type !!}</td>
            <td>
                {!! Form::open(['route' => ['reports.destroy', $report->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('reports.show', [$report->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('reports.edit', [$report->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>