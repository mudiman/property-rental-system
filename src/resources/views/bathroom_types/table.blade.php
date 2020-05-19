<table class="table table-responsive" id="bathroomTypes-table">
    <thead>
        <th>Title</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($bathroomTypes as $bathroomType)
        <tr>
            <td>{!! $bathroomType->title !!}</td>
            <td>{!! $bathroomType->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['bathroomTypes.destroy', $bathroomType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('bathroomTypes.show', [$bathroomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('bathroomTypes.edit', [$bathroomType->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>