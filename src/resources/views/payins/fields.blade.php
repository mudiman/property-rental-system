<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Bank Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bank_name', 'Bank Name:') !!}
    {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Account Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('account_number', 'Account Number:') !!}
    {!! Form::text('account_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Routing Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('routing_number', 'Routing Number:') !!}
    {!! Form::text('routing_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control']) !!}
</div>

<!-- Iban Field -->
<div class="form-group col-sm-6">
    {!! Form::label('iban', 'Iban:') !!}
    {!! Form::text('iban', null, ['class' => 'form-control']) !!}
</div>

<!-- Countrycode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('countryCode', 'Countrycode:') !!}
    {!! Form::text('countryCode', null, ['class' => 'form-control']) !!}
</div>

<!-- Sort Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sort_code', 'Sort Code:') !!}
    {!! Form::text('sort_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Bic Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bic', 'Bic:') !!}
    {!! Form::text('bic', null, ['class' => 'form-control']) !!}
</div>

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

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Gender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::text('gender', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Of Birth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_of_birth', 'Date Of Birth:') !!}
    <div class='input-group date datepicker'>
        {!! Form::date('date_of_birth', isset($payin->date_of_birth) ? Carbon\Carbon::parse($payin->date_of_birth)->format('Y-m-d'): Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Legal Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('legal_name', 'Legal Name:') !!}
    {!! Form::text('legal_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Tax Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tax_id', 'Tax Id:') !!}
    {!! Form::text('tax_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Locality Field -->
<div class="form-group col-sm-6">
    {!! Form::label('locality', 'Locality:') !!}
    {!! Form::text('locality', null, ['class' => 'form-control']) !!}
</div>

<!-- Postal Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('postal_code', 'Postal Code:') !!}
    {!! Form::text('postal_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Region Field -->
<div class="form-group col-sm-6">
    {!! Form::label('region', 'Region:') !!}
    {!! Form::text('region', null, ['class' => 'form-control']) !!}
</div>

<!-- Entity Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('entity_type', 'Entity Type:') !!}
    {!! Form::text('entity_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Nationality Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nationality', 'Nationality:') !!}
    {!! Form::text('nationality', null, ['class' => 'form-control']) !!}
</div>

<!-- Personal Id Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('personal_id_number', 'Personal Id Number:') !!}
    {!! Form::text('personal_id_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Billing Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('billing_address', 'Billing Address:') !!}
    {!! Form::textarea('billing_address', null, ['class' => 'form-control']) !!}
</div>

<!-- Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reference', 'Reference:') !!}
    {!! Form::text('reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Verified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('verified', 'Verified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('verified', false) !!}
        {!! Form::checkbox('verified', '1', null) !!}
    </label>
</div>

<!-- Default Field -->
<div class="form-group col-sm-6">
    {!! Form::label('default', 'Default:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('default', false) !!}
        {!! Form::checkbox('default', '1', null) !!}
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('payins.index') !!}" class="btn btn-default">Cancel</a>
</div>
