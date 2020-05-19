<table class="table table-responsive" id="references-table">
    <thead>
        <th>By User</th>
        <th>For User</th>
        <th>Comment</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($references as $reference)
        <tr>
            <td>{!! $reference->by_user !!}</td>
            <td>{!! $reference->for_user !!}</td>
            <td>{!! $reference->comment !!}</td>
            <td>
                {!! Form::open(['route' => ['references.destroy', $reference->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('references.show', [$reference->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('references.edit', [$reference->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>