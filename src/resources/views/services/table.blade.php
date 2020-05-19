<table class="table table-responsive" id="services-table">
    <thead>
        <th>Id</th>
        <th>Created By</th>
        <th>Title</th>
        <th>Type</th>
        <th>Description</th>
        <th>Area</th>
        <th>Lower Cap</th>
        <th>Upper Cap</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($services as $service)
        <tr>
            <td>{!! $service->id !!}</td>
            <td>{!! $service->created_by !!}</td>
            <td>{!! $service->title !!}</td>
            <td>{!! $service->type !!}</td>
            <td>{!! $service->description !!}</td>
            <td>{!! $service->area !!}</td>
            <td>{!! $service->lower_cap !!}</td>
            <td>{!! $service->upper_cap !!}</td>
            <td>
                {!! Form::open(['route' => ['services.destroy', $service->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('services.show', [$service->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('services.edit', [$service->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>