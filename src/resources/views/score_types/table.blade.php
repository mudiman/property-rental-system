<table class="table table-responsive" id="scoreTypes-table">
    <thead>
        <th>Title</th>
        <th>Category</th>
        <th>Roles</th>
        <th>Min Percentage</th>
        <th>Max Percentage</th>
        <th>Weight</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($scoreTypes as $scoreType)
        <tr>
            <td>{!! $scoreType->title !!}</td>
            <td>{!! $scoreType->category !!}</td>
            <td>{!! $scoreType->roles !!}</td>
            <td>{!! $scoreType->min_percentage !!}</td>
            <td>{!! $scoreType->max_percentage !!}</td>
            <td>{!! $scoreType->weight !!}</td>
            <td>{!! $scoreType->updated_by !!}</td>
            <td>{!! $scoreType->created_by !!}</td>
            <td>{!! $scoreType->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['scoreTypes.destroy', $scoreType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('scoreTypes.show', [$scoreType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('scoreTypes.edit', [$scoreType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>