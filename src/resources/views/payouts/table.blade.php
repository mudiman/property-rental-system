<table class="table table-responsive" id="payouts-table">
    <thead>
        <th>Payment Method</th>
        <th>User Id</th>
        <th>Holder Name</th>
        <th>Card Number</th>
        <th>Expire On Month</th>
        <th>Expire On Year</th>
        <th>Expiry</th>
        <th>Security Code</th>
        <th>Country</th>
        <th>Payout Data</th>
        <th>Payout Reference</th>
        <th>Smoor Reference</th>
        <th>User Reference</th>
        <th>Used</th>
        <th>Token</th>
        <th>Default</th>
        <th>Invalid</th>
        <th>Payment Error Type</th>
        <th>Payment Error Message</th>
        <th>Payment Error Code</th>
        <th>Payment Error Status</th>
        <th>Payment Error Param</th>
        <th>Payment Response</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($payouts as $payout)
        <tr>
            <td>{!! $payout->payment_method !!}</td>
            <td>{!! $payout->user_id !!}</td>
            <td>{!! $payout->holder_name !!}</td>
            <td>{!! $payout->card_number !!}</td>
            <td>{!! $payout->expire_on_month !!}</td>
            <td>{!! $payout->expire_on_year !!}</td>
            <td>{!! $payout->expiry !!}</td>
            <td>{!! $payout->security_code !!}</td>
            <td>{!! $payout->country !!}</td>
            <td>{!! $payout->payout_data !!}</td>
            <td>{!! $payout->payout_reference !!}</td>
            <td>{!! $payout->smoor_reference !!}</td>
            <td>{!! $payout->user_reference !!}</td>
            <td>{!! $payout->used !!}</td>
            <td>{!! $payout->token !!}</td>
            <td>{!! $payout->default !!}</td>
            <td>{!! $payout->invalid !!}</td>
            <td>{!! $payout->payment_error_type !!}</td>
            <td>{!! $payout->payment_error_message !!}</td>
            <td>{!! $payout->payment_error_code !!}</td>
            <td>{!! $payout->payment_error_status !!}</td>
            <td>{!! $payout->payment_error_param !!}</td>
            <td>{!! $payout->payment_response !!}</td>
            <td>{!! $payout->updated_by !!}</td>
            <td>{!! $payout->created_by !!}</td>
            <td>{!! $payout->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['payouts.destroy', $payout->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('payouts.show', [$payout->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('payouts.edit', [$payout->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>