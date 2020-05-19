<table class="table table-responsive" id="statistics-table">
    <thead>
        <th>Property Id</th>
        <th>User Id</th>
        <th>View Type</th>
        <th>Viewed Datetime</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($statistics as $statistic)
        <tr>
            <td>{!! $statistic->property_id !!}</td>
            <td>{!! $statistic->user_id !!}</td>
            <td>{!! $statistic->view_type !!}</td>
            <td>{!! $statistic->viewed_datetime !!}</td>
            <td>
                {!! Form::open(['route' => ['statistics.destroy', $statistic->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('statistics.show', [$statistic->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('statistics.edit', [$statistic->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>