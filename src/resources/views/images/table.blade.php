<table class="table table-responsive" id="images-table">
    <thead>
        <th>Path</th>
        <th>Bucket Name</th>
        <th>Filename</th>
        <th>Type</th>
        <th>Mimetype</th>
        <th>Primary</th>
        <th>Position</th>
        <th>Imageable Id</th>
        <th>Imageable Type</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($images as $image)
        <tr>
            <td>{!! $image->path !!}</td>
            <td>{!! $image->bucket_name !!}</td>
            <td>{!! $image->filename !!}</td>
            <td>{!! $image->type !!}</td>
            <td>{!! $image->mimetype !!}</td>
            <td>{!! $image->primary !!}</td>
            <td>{!! $image->position !!}</td>
            <td>{!! $image->imageable_id !!}</td>
            <td>{!! $image->imageable_type !!}</td>
            <td>{!! $image->updated_by !!}</td>
            <td>{!! $image->created_by !!}</td>
            <td>{!! $image->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['images.destroy', $image->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('images.show', [$image->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('images.edit', [$image->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>