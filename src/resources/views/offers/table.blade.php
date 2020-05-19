<table class="table table-responsive" id="offers-table">
    <thead>
        <th>Thread</th>
        <th>Tenant Id</th>
        <th>Property Id</th>
        <th>Landlord Id</th>
        <th>Property Pro Id</th>
        <th>Previous Offer Id</th>
        <th>Payout Id</th>
        <th>Landlord Payin Id</th>
        <th>Status</th>
        <th>Type</th>
        <th>Offer Expiry</th>
        <th>Holding Deposit Expiry</th>
        <th>Checkin</th>
        <th>Checkout</th>
        <th>Rent Per Month</th>
        <th>Rent Per Week</th>
        <th>Rent Per Night</th>
        <th>Currency</th>
        <th>Security Deposit Week</th>
        <th>Security Deposit Amount</th>
        <th>Security Holding Deposit Amount</th>
        <th>Special Condition</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($offers as $offer)
        <tr>
            <td>{!! $offer->thread !!}</td>
            <td>{!! $offer->tenant_id !!}</td>
            <td>{!! $offer->property_id !!}</td>
            <td>{!! $offer->landlord_id !!}</td>
            <td>{!! $offer->property_pro_id !!}</td>
            <td>{!! $offer->previous_offer_id !!}</td>
            <td>{!! $offer->payout_id !!}</td>
            <td>{!! $offer->landlord_payin_id !!}</td>
            <td>{!! $offer->status !!}</td>
            <td>{!! $offer->type !!}</td>
            <td>{!! $offer->offer_expiry !!}</td>
            <td>{!! $offer->holding_deposit_expiry !!}</td>
            <td>{!! $offer->checkin !!}</td>
            <td>{!! $offer->checkout !!}</td>
            <td>{!! $offer->rent_per_month !!}</td>
            <td>{!! $offer->rent_per_week !!}</td>
            <td>{!! $offer->rent_per_night !!}</td>
            <td>{!! $offer->currency !!}</td>
            <td>{!! $offer->security_deposit_week !!}</td>
            <td>{!! $offer->security_deposit_amount !!}</td>
            <td>{!! $offer->security_holding_deposit_amount !!}</td>
            <td>{!! $offer->special_condition !!}</td>
            <td>{!! $offer->updated_by !!}</td>
            <td>{!! $offer->created_by !!}</td>
            <td>{!! $offer->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['offers.destroy', $offer->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('offers.show', [$offer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('offers.edit', [$offer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>