<table class="table table-responsive" id="propertyPros-table">
    <thead>
        <th>Thread</th>
        <th>Landlord Id</th>
        <th>Property Pro Id</th>
        <th>Property Id</th>
        <th>Property Pro Payin Id</th>
        <th>Property Pro Sign Location</th>
        <th>Property Pro Sign Datetime</th>
        <th>Landlord Sign Location</th>
        <th>Landlord Sign Datetime</th>
        <th>Price Type</th>
        <th>Price</th>
        <th>Status</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($propertyPros as $propertyPro)
        <tr>
            <td>{!! $propertyPro->thread !!}</td>
            <td>{!! $propertyPro->landlord_id !!}</td>
            <td>{!! $propertyPro->property_pro_id !!}</td>
            <td>{!! $propertyPro->property_id !!}</td>
            <td>{!! $propertyPro->property_pro_payin_id !!}</td>
            <td>{!! $propertyPro->property_pro_sign_location !!}</td>
            <td>{!! $propertyPro->property_pro_sign_datetime !!}</td>
            <td>{!! $propertyPro->landlord_sign_location !!}</td>
            <td>{!! $propertyPro->landlord_sign_datetime !!}</td>
            <td>{!! $propertyPro->price_type !!}</td>
            <td>{!! $propertyPro->price !!}</td>
            <td>{!! $propertyPro->status !!}</td>
            <td>{!! $propertyPro->updated_by !!}</td>
            <td>{!! $propertyPro->created_by !!}</td>
            <td>{!! $propertyPro->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['propertyPros.destroy', $propertyPro->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('propertyPros.show', [$propertyPro->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('propertyPros.edit', [$propertyPro->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>