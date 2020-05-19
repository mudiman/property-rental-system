<table class="table table-responsive" id="transactions-table">
    <thead>
        <th>User Id</th>
        <th>Payin Id</th>
        <th>Payout Id</th>
        <th>Parent Transaction Id</th>
        <th>Transactionable Id</th>
        <th>Transactionable Type</th>
        <th>Title</th>
        <th>Description</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Currency</th>
        <th>Smoor Commission</th>
        <th>Payment Gateway Commission</th>
        <th>Landlord Commission</th>
        <th>Agency Commission</th>
        <th>Property Pro Commission</th>
        <th>Status</th>
        <th>Transaction Data</th>
        <th>Transaction Reference</th>
        <th>Smoor Reference</th>
        <th>Indempotent Key</th>
        <th>Dividen Done</th>
        <th>Payment Error Message</th>
        <th>Payment Error Type</th>
        <th>Payment Error Code</th>
        <th>Payment Error Status</th>
        <th>Payment Error Param</th>
        <th>Payment Response</th>
        <th>Refund Status</th>
        <th>Refund Reference</th>
        <th>Refund Response</th>
        <th>Retries</th>
        <th>Token Used</th>
        <th>Due Date</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($transactions as $transaction)
        <tr>
            <td>{!! $transaction->user_id !!}</td>
            <td>{!! $transaction->payin_id !!}</td>
            <td>{!! $transaction->payout_id !!}</td>
            <td>{!! $transaction->parent_transaction_id !!}</td>
            <td>{!! $transaction->transactionable_id !!}</td>
            <td>{!! $transaction->transactionable_type !!}</td>
            <td>{!! $transaction->title !!}</td>
            <td>{!! $transaction->description !!}</td>
            <td>{!! $transaction->type !!}</td>
            <td>{!! $transaction->amount !!}</td>
            <td>{!! $transaction->currency !!}</td>
            <td>{!! $transaction->smoor_commission !!}</td>
            <td>{!! $transaction->payment_gateway_commission !!}</td>
            <td>{!! $transaction->landlord_commission !!}</td>
            <td>{!! $transaction->agency_commission !!}</td>
            <td>{!! $transaction->property_pro_commission !!}</td>
            <td>{!! $transaction->status !!}</td>
            <td>{!! $transaction->transaction_data !!}</td>
            <td>{!! $transaction->transaction_reference !!}</td>
            <td>{!! $transaction->smoor_reference !!}</td>
            <td>{!! $transaction->indempotent_key !!}</td>
            <td>{!! $transaction->dividen_done !!}</td>
            <td>{!! $transaction->payment_error_message !!}</td>
            <td>{!! $transaction->payment_error_type !!}</td>
            <td>{!! $transaction->payment_error_code !!}</td>
            <td>{!! $transaction->payment_error_status !!}</td>
            <td>{!! $transaction->payment_error_param !!}</td>
            <td>{!! $transaction->payment_response !!}</td>
            <td>{!! $transaction->refund_status !!}</td>
            <td>{!! $transaction->refund_reference !!}</td>
            <td>{!! $transaction->refund_response !!}</td>
            <td>{!! $transaction->retries !!}</td>
            <td>{!! $transaction->token_used !!}</td>
            <td>{!! $transaction->due_date !!}</td>
            <td>{!! $transaction->updated_by !!}</td>
            <td>{!! $transaction->created_by !!}</td>
            <td>{!! $transaction->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['transactions.destroy', $transaction->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('transactions.show', [$transaction->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('transactions.edit', [$transaction->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>