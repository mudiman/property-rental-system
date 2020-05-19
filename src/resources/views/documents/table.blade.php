<table class="table table-responsive" id="documents-table">
    <thead>
        <th>Name</th>
        <th>Type</th>
        <th>Mimetype</th>
        <th>Issuing Country</th>
        <th>Verified</th>
        <th>Path</th>
        <th>Bucket Name</th>
        <th>Filename</th>
        <th>File Front Path</th>
        <th>File Front Filename</th>
        <th>File Front Mimetype</th>
        <th>File Back Path</th>
        <th>File Back Filename</th>
        <th>File Back Mimetype</th>
        <th>Documentable Id</th>
        <th>Documentable Type</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($documents as $document)
        <tr>
            <td>{!! $document->name !!}</td>
            <td>{!! $document->type !!}</td>
            <td>{!! $document->mimetype !!}</td>
            <td>{!! $document->issuing_country !!}</td>
            <td>{!! $document->verified !!}</td>
            <td>{!! $document->path !!}</td>
            <td>{!! $document->bucket_name !!}</td>
            <td>{!! $document->filename !!}</td>
            <td>{!! $document->file_front_path !!}</td>
            <td>{!! $document->file_front_filename !!}</td>
            <td>{!! $document->file_front_mimetype !!}</td>
            <td>{!! $document->file_back_path !!}</td>
            <td>{!! $document->file_back_filename !!}</td>
            <td>{!! $document->file_back_mimetype !!}</td>
            <td>{!! $document->documentable_id !!}</td>
            <td>{!! $document->documentable_type !!}</td>
            <td>{!! $document->updated_by !!}</td>
            <td>{!! $document->created_by !!}</td>
            <td>{!! $document->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['documents.destroy', $document->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('documents.show', [$document->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('documents.edit', [$document->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>