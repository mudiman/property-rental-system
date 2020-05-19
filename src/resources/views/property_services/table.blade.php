<table class="table table-responsive" id="propertyServices-table">
    <thead>
        <th>Property Pro Entity Id</th>
        <th>User Id</th>
        <th>Property Id</th>
        <th>Service Id</th>
        <th>Status</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($propertyServices as $propertyService)
        <tr>
            <td>{!! $propertyService->property_pro_entity_id !!}</td>
            <td>{!! $propertyService->user_id !!}</td>
            <td>{!! $propertyService->property_id !!}</td>
            <td>{!! $propertyService->service_id !!}</td>
            <td>{!! $propertyService->status !!}</td>
            <td>{!! $propertyService->updated_by !!}</td>
            <td>{!! $propertyService->created_by !!}</td>
            <td>{!! $propertyService->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['propertyServices.destroy', $propertyService->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('propertyServices.show', [$propertyService->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('propertyServices.edit', [$propertyService->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>