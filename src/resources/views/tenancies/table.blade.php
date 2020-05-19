<table class="table table-responsive" id="tenancies-table">
    <thead>
        <th>Tenant Id</th>
        <th>Property Id</th>
        <th>Offer Id</th>
        <th>Thread</th>
        <th>Parent Tenancy Id</th>
        <th>Landlord Id</th>
        <th>Property Pro Id</th>
        <th>Payout Id</th>
        <th>Landlord Payin Id</th>
        <th>Status</th>
        <th>Mode</th>
        <th>Previous Status</th>
        <th>Type</th>
        <th>Sign Expiry</th>
        <th>Checkin</th>
        <th>Checkout</th>
        <th>Actual Checkin</th>
        <th>Actual Checkout</th>
        <th>Due Date</th>
        <th>Due Amount</th>
        <th>Tenant Sign Location</th>
        <th>Tenant Sign Datetime</th>
        <th>Landlord Sign Location</th>
        <th>Landlord Sign Datetime</th>
        <th>Special Condition</th>
        <th>Landlord Notice Reminded</th>
        <th>Tenant Notice Reminded</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($tenancies as $tenancy)
        <tr>
            <td>{!! $tenancy->tenant_id !!}</td>
            <td>{!! $tenancy->property_id !!}</td>
            <td>{!! $tenancy->offer_id !!}</td>
            <td>{!! $tenancy->thread !!}</td>
            <td>{!! $tenancy->parent_tenancy_id !!}</td>
            <td>{!! $tenancy->landlord_id !!}</td>
            <td>{!! $tenancy->property_pro_id !!}</td>
            <td>{!! $tenancy->payout_id !!}</td>
            <td>{!! $tenancy->landlord_payin_id !!}</td>
            <td>{!! $tenancy->status !!}</td>
            <td>{!! $tenancy->mode !!}</td>
            <td>{!! $tenancy->previous_status !!}</td>
            <td>{!! $tenancy->type !!}</td>
            <td>{!! $tenancy->sign_expiry !!}</td>
            <td>{!! $tenancy->checkin !!}</td>
            <td>{!! $tenancy->checkout !!}</td>
            <td>{!! $tenancy->actual_checkin !!}</td>
            <td>{!! $tenancy->actual_checkout !!}</td>
            <td>{!! $tenancy->due_date !!}</td>
            <td>{!! $tenancy->due_amount !!}</td>
            <td>{!! $tenancy->tenant_sign_location !!}</td>
            <td>{!! $tenancy->tenant_sign_datetime !!}</td>
            <td>{!! $tenancy->landlord_sign_location !!}</td>
            <td>{!! $tenancy->landlord_sign_datetime !!}</td>
            <td>{!! $tenancy->special_condition !!}</td>
            <td>{!! $tenancy->landlord_notice_reminded !!}</td>
            <td>{!! $tenancy->tenant_notice_reminded !!}</td>
            <td>{!! $tenancy->updated_by !!}</td>
            <td>{!! $tenancy->created_by !!}</td>
            <td>{!! $tenancy->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['tenancies.destroy', $tenancy->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('tenancies.show', [$tenancy->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('tenancies.edit', [$tenancy->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>