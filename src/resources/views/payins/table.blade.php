<table class="table table-responsive" id="payins-table">
    <thead>
        <th>User Id</th>
        <th>Bank Name</th>
        <th>Account Number</th>
        <th>Routing Number</th>
        <th>Currency</th>
        <th>Iban</th>
        <th>Countrycode</th>
        <th>Sort Code</th>
        <th>Bic</th>
        <th>Ip</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>Date Of Birth</th>
        <th>Ssn</th>
        <th>Address</th>
        <th>Legal Name</th>
        <th>Tax Id</th>
        <th>Locality</th>
        <th>Postal Code</th>
        <th>Region</th>
        <th>Entity Type</th>
        <th>Nationality</th>
        <th>Personal Id Number</th>
        <th>Payment Gateway Identity Document</th>
        <th>Payment Gateway Identity Document Id</th>
        <th>Billing Address</th>
        <th>Payin Data</th>
        <th>Smoor Reference</th>
        <th>User Reference</th>
        <th>Reference</th>
        <th>Token</th>
        <th>Verified</th>
        <th>Payment Gateway Response</th>
        <th>Verification Response</th>
        <th>Default</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($payins as $payin)
        <tr>
            <td>{!! $payin->user_id !!}</td>
            <td>{!! $payin->bank_name !!}</td>
            <td>{!! $payin->account_number !!}</td>
            <td>{!! $payin->routing_number !!}</td>
            <td>{!! $payin->currency !!}</td>
            <td>{!! $payin->iban !!}</td>
            <td>{!! $payin->countryCode !!}</td>
            <td>{!! $payin->sort_code !!}</td>
            <td>{!! $payin->bic !!}</td>
            <td>{!! $payin->ip !!}</td>
            <td>{!! $payin->first_name !!}</td>
            <td>{!! $payin->last_name !!}</td>
            <td>{!! $payin->email !!}</td>
            <td>{!! $payin->phone !!}</td>
            <td>{!! $payin->gender !!}</td>
            <td>{!! $payin->date_of_birth !!}</td>
            <td>{!! $payin->ssn !!}</td>
            <td>{!! $payin->address !!}</td>
            <td>{!! $payin->legal_name !!}</td>
            <td>{!! $payin->tax_id !!}</td>
            <td>{!! $payin->locality !!}</td>
            <td>{!! $payin->postal_code !!}</td>
            <td>{!! $payin->region !!}</td>
            <td>{!! $payin->entity_type !!}</td>
            <td>{!! $payin->nationality !!}</td>
            <td>{!! $payin->personal_id_number !!}</td>
            <td>{!! $payin->payment_gateway_identity_document !!}</td>
            <td>{!! $payin->payment_gateway_identity_document_id !!}</td>
            <td>{!! $payin->billing_address !!}</td>
            <td>{!! $payin->payin_data !!}</td>
            <td>{!! $payin->smoor_reference !!}</td>
            <td>{!! $payin->user_reference !!}</td>
            <td>{!! $payin->reference !!}</td>
            <td>{!! $payin->token !!}</td>
            <td>{!! $payin->verified !!}</td>
            <td>{!! $payin->payment_gateway_response !!}</td>
            <td>{!! $payin->verification_response !!}</td>
            <td>{!! $payin->default !!}</td>
            <td>{!! $payin->updated_by !!}</td>
            <td>{!! $payin->created_by !!}</td>
            <td>{!! $payin->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['payins.destroy', $payin->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('payins.show', [$payin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('payins.edit', [$payin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>