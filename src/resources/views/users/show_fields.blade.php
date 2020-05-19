<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $user->id !!}</p>
</div>

<!-- First Name Field -->
<div class="form-group">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{!! $user->first_name !!}</p>
</div>

<!-- Last Name Field -->
<div class="form-group">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{!! $user->last_name !!}</p>
</div>

<!-- Username Field -->
<div class="form-group">
    {!! Form::label('username', 'Username:') !!}
    <p>{!! $user->username !!}</p>
</div>

<!-- Role Field -->
<div class="form-group">
    {!! Form::label('role', 'Role:') !!}
    <p>{!! $user->role !!}</p>
</div>

<!-- About Field -->
<div class="form-group">
    {!! Form::label('about', 'About:') !!}
    <p>{!! $user->about !!}</p>
</div>

<!-- About Smoor Score Service Field -->
<div class="form-group">
    {!! Form::label('about_smoor_score_service', 'About Smoor Score Service:') !!}
    <p>{!! $user->about_smoor_score_service !!}</p>
</div>

<!-- Gender Field -->
<div class="form-group">
    {!! Form::label('gender', 'Gender:') !!}
    <p>{!! $user->gender !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{!! $user->email !!}</p>
</div>

<!-- Cordinate Field -->
<div class="form-group">
    {!! Form::label('cordinate', 'Cordinate:') !!}
    <p>{!! isset($user->cordinate) ? $user->cordinate['lat'].",".$user->cordinate['lon']: null !!}</p>
</div>

<!-- Postcode Field -->
<div class="form-group">
    {!! Form::label('postcode', 'Postcode:') !!}
    <p>{!! $user->postcode !!}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', 'Address:') !!}
    <p>{!! $user->address !!}</p>
</div>

<!-- Location Field -->
<div class="form-group">
    {!! Form::label('location', 'Location:') !!}
    <p>{!! $user->location !!}</p>
</div>

<!-- Profession Field -->
<div class="form-group">
    {!! Form::label('profession', 'Profession:') !!}
    <p>{!! $user->profession !!}</p>
</div>

<!-- Mobile Field -->
<div class="form-group">
    {!! Form::label('mobile', 'Mobile:') !!}
    <p>{!! $user->mobile !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $user->status !!}</p>
</div>

<!-- Qualification Field -->
<div class="form-group">
    {!! Form::label('qualification', 'Qualification:') !!}
    <p>{!! $user->qualification !!}</p>
</div>

<!-- Arla Qualified Field -->
<div class="form-group">
    {!! Form::label('arla_qualified', 'Arla Qualified:') !!}
    <p>{!! $user->arla_qualified !!}</p>
</div>

<!-- Profile Picture Field -->
<div class="form-group">
    {!! Form::label('profile_picture', 'Profile Picture:') !!}
    <p>{!! $user->profile_picture !!}</p>
</div>

<!-- Email Verification Code Field -->
<div class="form-group">
    {!! Form::label('email_verification_code', 'Email Verification Code:') !!}
    <p>{!! $user->email_verification_code !!}</p>
</div>

<!-- Email Verification Code Expiry Field -->
<div class="form-group">
    {!! Form::label('email_verification_code_expiry', 'Email Verification Code Expiry:') !!}
    <p>{!! $user->email_verification_code_expiry !!}</p>
</div>

<!-- Forgot Password Verification Code Field -->
<div class="form-group">
    {!! Form::label('forgot_password_verification_code', 'Forgot Password Verification Code:') !!}
    <p>{!! $user->forgot_password_verification_code !!}</p>
</div>

<!-- Forgot Password Verification Code Expiry Field -->
<div class="form-group">
    {!! Form::label('forgot_password_verification_code_expiry', 'Forgot Password Verification Code Expiry:') !!}
    <p>{!! $user->forgot_password_verification_code_expiry !!}</p>
</div>

<!-- Sms Verification Code Field -->
<div class="form-group">
    {!! Form::label('sms_verification_code', 'Sms Verification Code:') !!}
    <p>{!! $user->sms_verification_code !!}</p>
</div>

<!-- Sms Verification Code Expiry Field -->
<div class="form-group">
    {!! Form::label('sms_verification_code_expiry', 'Sms Verification Code Expiry:') !!}
    <p>{!! $user->sms_verification_code_expiry !!}</p>
</div>

<!-- Password Field -->
<div class="form-group">
    {!! Form::label('password', 'Password:') !!}
    <p>{!! $user->password !!}</p>
</div>

<!-- Verified Field -->
<div class="form-group">
    {!! Form::label('verified', 'Verified:') !!}
    <p>{!! $user->verified !!}</p>
</div>

<!-- Admin Verified Field -->
<div class="form-group">
    {!! Form::label('admin_verified', 'Admin Verified:') !!}
    <p>{!! $user->admin_verified !!}</p>
</div>

<!-- Token Field -->
<div class="form-group">
    {!! Form::label('token', 'Token:') !!}
    <p>{!! $user->token !!}</p>
</div>

<!-- Remember Token Field -->
<div class="form-group">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    <p>{!! $user->remember_token !!}</p>
</div>

<!-- Secret Field -->
<div class="form-group">
    {!! Form::label('secret', 'Secret:') !!}
    <p>{!! $user->secret !!}</p>
</div>

<!-- Transaction Reputation Field -->
<div class="form-group">
    {!! Form::label('transaction_reputation', 'Transaction Reputation:') !!}
    <p>{!! $user->transaction_reputation !!}</p>
</div>

<!-- Transaction Reputation Max Field -->
<div class="form-group">
    {!! Form::label('transaction_reputation_max', 'Transaction Reputation Max:') !!}
    <p>{!! $user->transaction_reputation_max !!}</p>
</div>

<!-- Transaction Reputation Min Field -->
<div class="form-group">
    {!! Form::label('transaction_reputation_min', 'Transaction Reputation Min:') !!}
    <p>{!! $user->transaction_reputation_min !!}</p>
</div>

<!-- Conduct Reputation Field -->
<div class="form-group">
    {!! Form::label('conduct_reputation', 'Conduct Reputation:') !!}
    <p>{!! $user->conduct_reputation !!}</p>
</div>

<!-- Conduct Reputation Max Field -->
<div class="form-group">
    {!! Form::label('conduct_reputation_max', 'Conduct Reputation Max:') !!}
    <p>{!! $user->conduct_reputation_max !!}</p>
</div>

<!-- Conduct Reputation Min Field -->
<div class="form-group">
    {!! Form::label('conduct_reputation_min', 'Conduct Reputation Min:') !!}
    <p>{!! $user->conduct_reputation_min !!}</p>
</div>

<!-- Service Reputation Field -->
<div class="form-group">
    {!! Form::label('service_reputation', 'Service Reputation:') !!}
    <p>{!! $user->service_reputation !!}</p>
</div>

<!-- Service Reputation Max Field -->
<div class="form-group">
    {!! Form::label('service_reputation_max', 'Service Reputation Max:') !!}
    <p>{!! $user->service_reputation_max !!}</p>
</div>

<!-- Service Reputation Min Field -->
<div class="form-group">
    {!! Form::label('service_reputation_min', 'Service Reputation Min:') !!}
    <p>{!! $user->service_reputation_min !!}</p>
</div>

<!-- Date Of Birth Field -->
<div class="form-group">
    {!! Form::label('date_of_birth', 'Date Of Birth:') !!}
    <p>{!! $user->date_of_birth !!}</p>
</div>

<!-- Phone Field -->
<div class="form-group">
    {!! Form::label('phone', 'Phone:') !!}
    <p>{!! $user->phone !!}</p>
</div>

<!-- School Field -->
<div class="form-group">
    {!! Form::label('school', 'School:') !!}
    <p>{!! $user->school !!}</p>
</div>

<!-- Available To Move On Field -->
<div class="form-group">
    {!! Form::label('available_to_move_on', 'Available To Move On:') !!}
    <p>{!! $user->available_to_move_on !!}</p>
</div>

<!-- Commission Charge Field -->
<div class="form-group">
    {!! Form::label('commission_charge', 'Commission Charge:') !!}
    <p>{!! $user->commission_charge !!}</p>
</div>

<!-- Area Covered Field -->
<div class="form-group">
    {!! Form::label('area_covered', 'Area Covered:') !!}
    <p>{!! $user->area_covered !!}</p>
</div>

<!-- Place Of Work Field -->
<div class="form-group">
    {!! Form::label('place_of_work', 'Place Of Work:') !!}
    <p>{!! $user->place_of_work !!}</p>
</div>

<!-- Languages Field -->
<div class="form-group">
    {!! Form::label('languages', 'Languages:') !!}
    <p>{!! $user->languages !!}</p>
</div>

<!-- Current Residence Postcode Field -->
<div class="form-group">
    {!! Form::label('current_residence_postcode', 'Current Residence Postcode:') !!}
    <p>{!! $user->current_residence_postcode !!}</p>
</div>

<!-- Current Residence Property Type Field -->
<div class="form-group">
    {!! Form::label('current_residence_property_type', 'Current Residence Property Type:') !!}
    <p>{!! $user->current_residence_property_type !!}</p>
</div>

<!-- Current Residence Bedrooms Field -->
<div class="form-group">
    {!! Form::label('current_residence_bedrooms', 'Current Residence Bedrooms:') !!}
    <p>{!! $user->current_residence_bedrooms !!}</p>
</div>

<!-- Current Residence Bathrooms Field -->
<div class="form-group">
    {!! Form::label('current_residence_bathrooms', 'Current Residence Bathrooms:') !!}
    <p>{!! $user->current_residence_bathrooms !!}</p>
</div>

<!-- Current Contract Type Field -->
<div class="form-group">
    {!! Form::label('current_contract_type', 'Current Contract Type:') !!}
    <p>{!! $user->current_contract_type !!}</p>
</div>

<!-- Current Contract Start Date Field -->
<div class="form-group">
    {!! Form::label('current_contract_start_date', 'Current Contract Start Date:') !!}
    <p>{!! $user->current_contract_start_date !!}</p>
</div>

<!-- Current Contract End Date Field -->
<div class="form-group">
    {!! Form::label('current_contract_end_date', 'Current Contract End Date:') !!}
    <p>{!! $user->current_contract_end_date !!}</p>
</div>

<!-- Current Rent Per Month Field -->
<div class="form-group">
    {!! Form::label('current_rent_per_month', 'Current Rent Per Month:') !!}
    <p>{!! $user->current_rent_per_month !!}</p>
</div>

<!-- Employment Status Field -->
<div class="form-group">
    {!! Form::label('employment_status', 'Employment Status:') !!}
    <p>{!! $user->employment_status !!}</p>
</div>

<!-- Previous Employer Field -->
<div class="form-group">
    {!! Form::label('previous_employer', 'Previous Employer:') !!}
    <p>{!! $user->previous_employer !!}</p>
</div>

<!-- Experience Years Field -->
<div class="form-group">
    {!! Form::label('experience_years', 'Experience Years:') !!}
    <p>{!! $user->experience_years !!}</p>
</div>

<!-- Previous Job Function Field -->
<div class="form-group">
    {!! Form::label('previous_job_function', 'Previous Job Function:') !!}
    <p>{!! $user->previous_job_function !!}</p>
</div>

<!-- Recent Search Query Field -->
<div class="form-group">
    {!! Form::label('recent_search_query', 'Recent Search Query:') !!}
    <p>{!! $user->recent_search_query !!}</p>
</div>

<!-- Extra Info Field -->
<div class="form-group">
    {!! Form::label('extra_info', 'Extra Info:') !!}
    <p>{!! $user->extra_info !!}</p>
</div>

<!-- Currency Field -->
<div class="form-group">
    {!! Form::label('currency', 'Currency:') !!}
    <p>{!! $user->currency !!}</p>
</div>

<!-- Push Notification Messages Field -->
<div class="form-group">
    {!! Form::label('push_notification_messages', 'Push Notification Messages:') !!}
    <p>{!! $user->push_notification_messages !!}</p>
</div>

<!-- Push Notification Viewings Field -->
<div class="form-group">
    {!! Form::label('push_notification_viewings', 'Push Notification Viewings:') !!}
    <p>{!! $user->push_notification_viewings !!}</p>
</div>

<!-- Push Notification Offers Field -->
<div class="form-group">
    {!! Form::label('push_notification_offers', 'Push Notification Offers:') !!}
    <p>{!! $user->push_notification_offers !!}</p>
</div>

<!-- Push Notification Other Field -->
<div class="form-group">
    {!! Form::label('push_notification_other', 'Push Notification Other:') !!}
    <p>{!! $user->push_notification_other !!}</p>
</div>

<!-- Email Notification Messages Field -->
<div class="form-group">
    {!! Form::label('email_notification_messages', 'Email Notification Messages:') !!}
    <p>{!! $user->email_notification_messages !!}</p>
</div>

<!-- Email Notification Viewings Field -->
<div class="form-group">
    {!! Form::label('email_notification_viewings', 'Email Notification Viewings:') !!}
    <p>{!! $user->email_notification_viewings !!}</p>
</div>

<!-- Email Notification Offers Field -->
<div class="form-group">
    {!! Form::label('email_notification_offers', 'Email Notification Offers:') !!}
    <p>{!! $user->email_notification_offers !!}</p>
</div>

<!-- Email Notification Other Field -->
<div class="form-group">
    {!! Form::label('email_notification_other', 'Email Notification Other:') !!}
    <p>{!! $user->email_notification_other !!}</p>
</div>

<!-- Text Notification Messages Field -->
<div class="form-group">
    {!! Form::label('text_notification_messages', 'Text Notification Messages:') !!}
    <p>{!! $user->text_notification_messages !!}</p>
</div>

<!-- Text Notification Viewings Field -->
<div class="form-group">
    {!! Form::label('text_notification_viewings', 'Text Notification Viewings:') !!}
    <p>{!! $user->text_notification_viewings !!}</p>
</div>

<!-- Text Notification Offers Field -->
<div class="form-group">
    {!! Form::label('text_notification_offers', 'Text Notification Offers:') !!}
    <p>{!! $user->text_notification_offers !!}</p>
</div>

<!-- Text Notification Other Field -->
<div class="form-group">
    {!! Form::label('text_notification_other', 'Text Notification Other:') !!}
    <p>{!! $user->text_notification_other !!}</p>
</div>

<!-- Account Reference Field -->
<div class="form-group">
    {!! Form::label('account_reference', 'Account Reference:') !!}
    <p>{!! $user->account_reference !!}</p>
</div>

<!-- Customer Reference Field -->
<div class="form-group">
    {!! Form::label('customer_reference', 'Customer Reference:') !!}
    <p>{!! $user->customer_reference !!}</p>
</div>

<!-- Timezone Field -->
<div class="form-group">
    {!! Form::label('timezone', 'Timezone:') !!}
    <p>{!! $user->timezone !!}</p>
</div>

<!-- Configuration Field -->
<div class="form-group">
    {!! Form::label('configuration', 'Configuration:') !!}
    <p>{!! $user->configuration !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $user->deleted_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $user->updated_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $user->created_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $user->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $user->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $user->deleted_by !!}</p>
</div>