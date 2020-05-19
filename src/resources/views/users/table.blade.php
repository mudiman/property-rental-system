<table class="table table-responsive" id="users-table">
    <thead>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Username</th>
        <th>Role</th>
        <th>About</th>
        <th>About Smoor Score Service</th>
        <th>Gender</th>
        <th>Email</th>
        <th>Cordinate</th>
        <th>Postcode</th>
        <th>Address</th>
        <th>Location</th>
        <th>Profession</th>
        <th>Mobile</th>
        <th>Status</th>
        <th>Qualification</th>
        <th>Arla Qualified</th>
        <th>Profile Picture</th>
        <th>Email Verification Code</th>
        <th>Email Verification Code Expiry</th>
        <th>Forgot Password Verification Code</th>
        <th>Forgot Password Verification Code Expiry</th>
        <th>Sms Verification Code</th>
        <th>Sms Verification Code Expiry</th>
        <th>Password</th>
        <th>Verified</th>
        <th>Admin Verified</th>
        <th>Token</th>
        <th>Remember Token</th>
        <th>Secret</th>
        <th>Transaction Reputation</th>
        <th>Transaction Reputation Max</th>
        <th>Transaction Reputation Min</th>
        <th>Conduct Reputation</th>
        <th>Conduct Reputation Max</th>
        <th>Conduct Reputation Min</th>
        <th>Service Reputation</th>
        <th>Service Reputation Max</th>
        <th>Service Reputation Min</th>
        <th>Date Of Birth</th>
        <th>Phone</th>
        <th>School</th>
        <th>Available To Move On</th>
        <th>Commission Charge</th>
        <th>Area Covered</th>
        <th>Place Of Work</th>
        <th>Languages</th>
        <th>Current Residence Postcode</th>
        <th>Current Residence Property Type</th>
        <th>Current Residence Bedrooms</th>
        <th>Current Residence Bathrooms</th>
        <th>Current Contract Type</th>
        <th>Current Contract Start Date</th>
        <th>Current Contract End Date</th>
        <th>Current Rent Per Month</th>
        <th>Employment Status</th>
        <th>Previous Employer</th>
        <th>Experience Years</th>
        <th>Previous Job Function</th>
        <th>Recent Search Query</th>
        <th>Extra Info</th>
        <th>Currency</th>
        <th>Push Notification Messages</th>
        <th>Push Notification Viewings</th>
        <th>Push Notification Offers</th>
        <th>Push Notification Other</th>
        <th>Email Notification Messages</th>
        <th>Email Notification Viewings</th>
        <th>Email Notification Offers</th>
        <th>Email Notification Other</th>
        <th>Text Notification Messages</th>
        <th>Text Notification Viewings</th>
        <th>Text Notification Offers</th>
        <th>Text Notification Other</th>
        <th>Account Reference</th>
        <th>Customer Reference</th>
        <th>Timezone</th>
        <th>Configuration</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{!! $user->first_name !!}</td>
            <td>{!! $user->last_name !!}</td>
            <td>{!! $user->username !!}</td>
            <td>{!! $user->role !!}</td>
            <td>{!! $user->about !!}</td>
            <td>{!! $user->about_smoor_score_service !!}</td>
            <td>{!! $user->gender !!}</td>
            <td>{!! $user->email !!}</td>
            <td>{!! $user->cordinate !!}</td>
            <td>{!! $user->postcode !!}</td>
            <td>{!! $user->address !!}</td>
            <td>{!! $user->location !!}</td>
            <td>{!! $user->profession !!}</td>
            <td>{!! $user->mobile !!}</td>
            <td>{!! $user->status !!}</td>
            <td>{!! $user->qualification !!}</td>
            <td>{!! $user->arla_qualified !!}</td>
            <td>{!! $user->profile_picture !!}</td>
            <td>{!! $user->email_verification_code !!}</td>
            <td>{!! $user->email_verification_code_expiry !!}</td>
            <td>{!! $user->forgot_password_verification_code !!}</td>
            <td>{!! $user->forgot_password_verification_code_expiry !!}</td>
            <td>{!! $user->sms_verification_code !!}</td>
            <td>{!! $user->sms_verification_code_expiry !!}</td>
            <td>{!! $user->password !!}</td>
            <td>{!! $user->verified !!}</td>
            <td>{!! $user->admin_verified !!}</td>
            <td>{!! $user->token !!}</td>
            <td>{!! $user->remember_token !!}</td>
            <td>{!! $user->secret !!}</td>
            <td>{!! $user->transaction_reputation !!}</td>
            <td>{!! $user->transaction_reputation_max !!}</td>
            <td>{!! $user->transaction_reputation_min !!}</td>
            <td>{!! $user->conduct_reputation !!}</td>
            <td>{!! $user->conduct_reputation_max !!}</td>
            <td>{!! $user->conduct_reputation_min !!}</td>
            <td>{!! $user->service_reputation !!}</td>
            <td>{!! $user->service_reputation_max !!}</td>
            <td>{!! $user->service_reputation_min !!}</td>
            <td>{!! $user->date_of_birth !!}</td>
            <td>{!! $user->phone !!}</td>
            <td>{!! $user->school !!}</td>
            <td>{!! $user->available_to_move_on !!}</td>
            <td>{!! $user->commission_charge !!}</td>
            <td>{!! $user->area_covered !!}</td>
            <td>{!! $user->place_of_work !!}</td>
            <td>{!! $user->languages !!}</td>
            <td>{!! $user->current_residence_postcode !!}</td>
            <td>{!! $user->current_residence_property_type !!}</td>
            <td>{!! $user->current_residence_bedrooms !!}</td>
            <td>{!! $user->current_residence_bathrooms !!}</td>
            <td>{!! $user->current_contract_type !!}</td>
            <td>{!! $user->current_contract_start_date !!}</td>
            <td>{!! $user->current_contract_end_date !!}</td>
            <td>{!! $user->current_rent_per_month !!}</td>
            <td>{!! $user->employment_status !!}</td>
            <td>{!! $user->previous_employer !!}</td>
            <td>{!! $user->experience_years !!}</td>
            <td>{!! $user->previous_job_function !!}</td>
            <td>{!! $user->recent_search_query !!}</td>
            <td>{!! $user->extra_info !!}</td>
            <td>{!! $user->currency !!}</td>
            <td>{!! $user->push_notification_messages !!}</td>
            <td>{!! $user->push_notification_viewings !!}</td>
            <td>{!! $user->push_notification_offers !!}</td>
            <td>{!! $user->push_notification_other !!}</td>
            <td>{!! $user->email_notification_messages !!}</td>
            <td>{!! $user->email_notification_viewings !!}</td>
            <td>{!! $user->email_notification_offers !!}</td>
            <td>{!! $user->email_notification_other !!}</td>
            <td>{!! $user->text_notification_messages !!}</td>
            <td>{!! $user->text_notification_viewings !!}</td>
            <td>{!! $user->text_notification_offers !!}</td>
            <td>{!! $user->text_notification_other !!}</td>
            <td>{!! $user->account_reference !!}</td>
            <td>{!! $user->customer_reference !!}</td>
            <td>{!! $user->timezone !!}</td>
            <td>{!! $user->configuration !!}</td>
            <td>{!! $user->updated_by !!}</td>
            <td>{!! $user->created_by !!}</td>
            <td>{!! $user->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>