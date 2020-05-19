<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Username Field -->
<div class="form-group col-sm-6">
    {!! Form::label('username', 'Username:') !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>

<!-- Role Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role', 'Role:') !!}
    {!! Form::text('role', isset($user->role) ? $user->role: \App\Models\User::ROLE_TENANT, ['class' => 'form-control']) !!}
</div>

<!-- About Field -->
<div class="form-group col-sm-6">
    {!! Form::label('about', 'About:') !!}
    {!! Form::text('about', null, ['class' => 'form-control']) !!}
</div>

<!-- About Smoor Score Service Field -->
<div class="form-group col-sm-6">
    {!! Form::label('about_smoor_score_service', 'About Smoor Score Service:') !!}
    {!! Form::text('about_smoor_score_service', null, ['class' => 'form-control']) !!}
</div>

<!-- Gender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::text('gender', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Cordinate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cordinate', 'Cordinate:') !!}
    {!! Form::text('cordinate', isset($user->cordinate) ? $user->cordinate['lat'].",".$user->cordinate['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Postcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('postcode', 'Postcode:') !!}
    {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('location', 'Location:') !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
</div>

<!-- Profession Field -->
<div class="form-group col-sm-6">
    {!! Form::label('profession', 'Profession:') !!}
    {!! Form::text('profession', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Qualification Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qualification', 'Qualification:') !!}
    {!! Form::text('qualification', null, ['class' => 'form-control']) !!}
</div>

<!-- Arla Qualified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arla_qualified', 'Arla Qualified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('arla_qualified', false) !!}
        {!! Form::checkbox('arla_qualified', '1', null) !!}
    </label>
</div>

<!-- Profile Picture Field -->
<div class="form-group col-sm-6">
    {!! Form::label('profile_picture', 'Profile Picture:') !!}
    {!! Form::text('profile_picture', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Verification Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_verification_code', 'Email Verification Code:') !!}
    {!! Form::text('email_verification_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Verification Code Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_verification_code_expiry', 'Email Verification Code Expiry:') !!}
    {!! Form::date('email_verification_code_expiry', isset($user->email_verification_code_expiry) ? Carbon\Carbon::parse($user->email_verification_code_expiry)->toDateTimeString(): null, ['class' => 'form-control']) !!}
</div>

<!-- Forgot Password Verification Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('forgot_password_verification_code', 'Forgot Password Verification Code:') !!}
    {!! Form::text('forgot_password_verification_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Forgot Password Verification Code Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('forgot_password_verification_code_expiry', 'Forgot Password Verification Code Expiry:') !!}
    {!! Form::date('forgot_password_verification_code_expiry', isset($user->forgot_password_verification_code_expiry) ? Carbon\Carbon::parse($user->forgot_password_verification_code_expiry)->toDateTimeString(): null, ['class' => 'form-control']) !!}
</div>

<!-- Sms Verification Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sms_verification_code', 'Sms Verification Code:') !!}
    {!! Form::text('sms_verification_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Sms Verification Code Expiry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sms_verification_code_expiry', 'Sms Verification Code Expiry:') !!}
    {!! Form::date('sms_verification_code_expiry', isset($user->sms_verification_code_expiry) ? Carbon\Carbon::parse($user->sms_verification_code_expiry)->toDateTimeString(): null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Verified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('verified', 'Verified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('verified', false) !!}
        {!! Form::checkbox('verified', '1', null) !!}
    </label>
</div>

<!-- Admin Verified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('admin_verified', 'Admin Verified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('admin_verified', false) !!}
        {!! Form::checkbox('admin_verified', '1', null) !!}
    </label>
</div>

<!-- Secret Field -->
<div class="form-group col-sm-6">
    {!! Form::label('secret', 'Secret:') !!}
    {!! Form::text('secret', null, ['class' => 'form-control']) !!}
</div>

<!-- Transaction Reputation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transaction_reputation', 'Transaction Reputation:') !!}
    {!! Form::number('transaction_reputation', null, ['class' => 'form-control']) !!}
</div>

<!-- Transaction Reputation Max Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transaction_reputation_max', 'Transaction Reputation Max:') !!}
    {!! Form::number('transaction_reputation_max', null, ['class' => 'form-control']) !!}
</div>

<!-- Transaction Reputation Min Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transaction_reputation_min', 'Transaction Reputation Min:') !!}
    {!! Form::number('transaction_reputation_min', null, ['class' => 'form-control']) !!}
</div>

<!-- Conduct Reputation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('conduct_reputation', 'Conduct Reputation:') !!}
    {!! Form::number('conduct_reputation', null, ['class' => 'form-control']) !!}
</div>

<!-- Conduct Reputation Max Field -->
<div class="form-group col-sm-6">
    {!! Form::label('conduct_reputation_max', 'Conduct Reputation Max:') !!}
    {!! Form::number('conduct_reputation_max', null, ['class' => 'form-control']) !!}
</div>

<!-- Conduct Reputation Min Field -->
<div class="form-group col-sm-6">
    {!! Form::label('conduct_reputation_min', 'Conduct Reputation Min:') !!}
    {!! Form::number('conduct_reputation_min', null, ['class' => 'form-control']) !!}
</div>

<!-- Service Reputation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('service_reputation', 'Service Reputation:') !!}
    {!! Form::number('service_reputation', null, ['class' => 'form-control']) !!}
</div>

<!-- Service Reputation Max Field -->
<div class="form-group col-sm-6">
    {!! Form::label('service_reputation_max', 'Service Reputation Max:') !!}
    {!! Form::number('service_reputation_max', null, ['class' => 'form-control']) !!}
</div>

<!-- Service Reputation Min Field -->
<div class="form-group col-sm-6">
    {!! Form::label('service_reputation_min', 'Service Reputation Min:') !!}
    {!! Form::number('service_reputation_min', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Of Birth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_of_birth', 'Date Of Birth:') !!}
    {!! Form::date('date_of_birth', isset($user->date_of_birth) ? Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- School Field -->
<div class="form-group col-sm-6">
    {!! Form::label('school', 'School:') !!}
    {!! Form::text('school', null, ['class' => 'form-control']) !!}
</div>

<!-- Available To Move On Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_to_move_on', 'Available To Move On:') !!}
    {!! Form::date('available_to_move_on', isset($user->available_to_move_on) ? Carbon\Carbon::parse($user->available_to_move_on)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
</div>

<!-- Commission Charge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('commission_charge', 'Commission Charge:') !!}
    {!! Form::number('commission_charge', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Covered Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_covered', 'Area Covered:') !!}
    {!! Form::text('area_covered', null, ['class' => 'form-control']) !!}
</div>

<!-- Place Of Work Field -->
<div class="form-group col-sm-6">
    {!! Form::label('place_of_work', 'Place Of Work:') !!}
    {!! Form::text('place_of_work', null, ['class' => 'form-control']) !!}
</div>

<!-- Languages Field -->
<div class="form-group col-sm-6">
    {!! Form::label('languages', 'Languages:') !!}
    {!! Form::text('languages', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Residence Postcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_residence_postcode', 'Current Residence Postcode:') !!}
    {!! Form::text('current_residence_postcode', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Residence Property Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_residence_property_type', 'Current Residence Property Type:') !!}
    {!! Form::text('current_residence_property_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Residence Bedrooms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_residence_bedrooms', 'Current Residence Bedrooms:') !!}
    {!! Form::number('current_residence_bedrooms', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Residence Bathrooms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_residence_bathrooms', 'Current Residence Bathrooms:') !!}
    {!! Form::number('current_residence_bathrooms', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Contract Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_contract_type', 'Current Contract Type:') !!}
    {!! Form::text('current_contract_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Contract Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_contract_start_date', 'Current Contract Start Date:') !!}
    {!! Form::date('current_contract_start_date', isset($user->current_contract_start_date) ? Carbon\Carbon::parse($user->current_contract_start_date)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
</div>

<!-- Current Contract End Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_contract_end_date', 'Current Contract End Date:') !!}
    {!! Form::date('current_contract_end_date', isset($user->current_contract_end_date) ? Carbon\Carbon::parse($user->current_contract_end_date)->format('Y-m-d'): null, ['class' => 'form-control']) !!}
</div>

<!-- Current Rent Per Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current_rent_per_month', 'Current Rent Per Month:') !!}
    {!! Form::number('current_rent_per_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Employment Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('employment_status', 'Employment Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('employment_status', false) !!}
        {!! Form::checkbox('employment_status', '1', null) !!}
    </label>
</div>

<!-- Previous Employer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('previous_employer', 'Previous Employer:') !!}
    {!! Form::text('previous_employer', null, ['class' => 'form-control']) !!}
</div>

<!-- Experience Years Field -->
<div class="form-group col-sm-6">
    {!! Form::label('experience_years', 'Experience Years:') !!}
    {!! Form::number('experience_years', null, ['class' => 'form-control']) !!}
</div>

<!-- Previous Job Function Field -->
<div class="form-group col-sm-6">
    {!! Form::label('previous_job_function', 'Previous Job Function:') !!}
    {!! Form::text('previous_job_function', null, ['class' => 'form-control']) !!}
</div>

<!-- Recent Search Query Field -->
<div class="form-group col-sm-6">
    {!! Form::label('recent_search_query', 'Recent Search Query:') !!}
    {!! Form::text('recent_search_query', null, ['class' => 'form-control']) !!}
</div>

<!-- Extra Info Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('extra_info', 'Extra Info:') !!}
    {!! Form::textarea('extra_info', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control']) !!}
</div>

<!-- Push Notification Messages Field -->
<div class="form-group col-sm-6">
    {!! Form::label('push_notification_messages', 'Push Notification Messages:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('push_notification_messages', false) !!}
        {!! Form::checkbox('push_notification_messages', '1', null) !!}
    </label>
</div>

<!-- Push Notification Viewings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('push_notification_viewings', 'Push Notification Viewings:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('push_notification_viewings', false) !!}
        {!! Form::checkbox('push_notification_viewings', '1', null) !!}
    </label>
</div>

<!-- Push Notification Offers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('push_notification_offers', 'Push Notification Offers:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('push_notification_offers', false) !!}
        {!! Form::checkbox('push_notification_offers', '1', null) !!}
    </label>
</div>

<!-- Push Notification Other Field -->
<div class="form-group col-sm-6">
    {!! Form::label('push_notification_other', 'Push Notification Other:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('push_notification_other', false) !!}
        {!! Form::checkbox('push_notification_other', '1', null) !!}
    </label>
</div>

<!-- Email Notification Messages Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_notification_messages', 'Email Notification Messages:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('email_notification_messages', false) !!}
        {!! Form::checkbox('email_notification_messages', '1', null) !!}
    </label>
</div>

<!-- Email Notification Viewings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_notification_viewings', 'Email Notification Viewings:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('email_notification_viewings', false) !!}
        {!! Form::checkbox('email_notification_viewings', '1', null) !!}
    </label>
</div>

<!-- Email Notification Offers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_notification_offers', 'Email Notification Offers:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('email_notification_offers', false) !!}
        {!! Form::checkbox('email_notification_offers', '1', null) !!}
    </label>
</div>

<!-- Email Notification Other Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_notification_other', 'Email Notification Other:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('email_notification_other', false) !!}
        {!! Form::checkbox('email_notification_other', '1', null) !!}
    </label>
</div>

<!-- Text Notification Messages Field -->
<div class="form-group col-sm-6">
    {!! Form::label('text_notification_messages', 'Text Notification Messages:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('text_notification_messages', false) !!}
        {!! Form::checkbox('text_notification_messages', '1', null) !!}
    </label>
</div>

<!-- Text Notification Viewings Field -->
<div class="form-group col-sm-6">
    {!! Form::label('text_notification_viewings', 'Text Notification Viewings:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('text_notification_viewings', false) !!}
        {!! Form::checkbox('text_notification_viewings', '1', null) !!}
    </label>
</div>

<!-- Text Notification Offers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('text_notification_offers', 'Text Notification Offers:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('text_notification_offers', false) !!}
        {!! Form::checkbox('text_notification_offers', '1', null) !!}
    </label>
</div>

<!-- Text Notification Other Field -->
<div class="form-group col-sm-6">
    {!! Form::label('text_notification_other', 'Text Notification Other:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('text_notification_other', false) !!}
        {!! Form::checkbox('text_notification_other', '1', null) !!}
    </label>
</div>

<!-- Account Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('account_reference', 'Account Reference:') !!}
    {!! Form::text('account_reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_reference', 'Customer Reference:') !!}
    {!! Form::text('customer_reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Timezone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('timezone', 'Timezone:') !!}
    {!! Form::text('timezone', null, ['class' => 'form-control']) !!}
</div>

<!-- Configuration Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('configuration', 'Configuration:') !!}
    {!! Form::textarea('configuration', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
