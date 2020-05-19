<!-- Landlord Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landlord_id', 'Landlord Id:') !!}
    {!! Form::number('landlord_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Profile Picture Field -->
<div class="form-group col-sm-6">
    {!! Form::label('profile_picture', 'Profile Picture:') !!}
    {!! Form::text('profile_picture', null, ['class' => 'form-control']) !!}
</div>

<!-- Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reference', 'Reference:') !!}
    {!! Form::text('reference', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Summary Field -->
<div class="form-group col-sm-6">
    {!! Form::label('summary', 'Summary:') !!}
    {!! Form::text('summary', null, ['class' => 'form-control']) !!}
</div>

<!-- Letting Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('letting_type', 'Letting Type:') !!}
    {!! Form::text('letting_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Property Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('property_type', 'Property Type:') !!}
    {!! Form::text('property_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Room Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('room_type', 'Room Type:') !!}
    {!! Form::text('room_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Room Suitable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('room_suitable', 'Room Suitable:') !!}
    {!! Form::text('room_suitable', null, ['class' => 'form-control']) !!}
</div>

<!-- Bathroom Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bathroom_type', 'Bathroom Type:') !!}
    {!! Form::text('bathroom_type', null, ['class' => 'form-control']) !!}
</div>

<!-- People Living Field -->
<div class="form-group col-sm-6">
    {!! Form::label('people_living', 'People Living:') !!}
    {!! Form::number('people_living', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', $statuses) !!}
</div>

<!-- Completion Phase Field -->
<div class="form-group col-sm-6">
    {!! Form::label('completion_phase', 'Completion Phase:') !!}
    {!! Form::text('completion_phase', null, ['class' => 'form-control']) !!}
</div>

<!-- Available Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_date', 'Available Date:') !!}
    <div class='input-group date datepicker'>
        {!! Form::date('available_date', isset($property->available_date) ? Carbon\Carbon::parse($property->available_date)->format('Y-m-d'): Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<!-- Cordinate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cordinate', 'Cordinate:') !!}
    {!! Form::text('cordinate', isset($property->cordinate) ? $property->cordinate['lat'].",".$property->cordinate['lon']: null, ['class' => 'form-control']) !!}
</div>

<!-- Postcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('postcode', 'Postcode:') !!}
    {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
</div>

<!-- Door Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('door_number', 'Door Number:') !!}
    {!! Form::text('door_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Street Field -->
<div class="form-group col-sm-6">
    {!! Form::label('street', 'Street:') !!}
    {!! Form::text('street', null, ['class' => 'form-control']) !!}
</div>

<!-- City Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- Verified Field -->
<div class="form-group col-sm-6">
    {!! Form::label('verified', 'Verified:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('verified', false) !!}
        {!! Form::checkbox('verified', '1', null) !!} 1
    </label>
</div>

<!-- Apartment Building Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apartment_building', 'Apartment Building:') !!}
    {!! Form::text('apartment_building', null, ['class' => 'form-control']) !!}
</div>

<!-- Floors Field -->
<div class="form-group col-sm-6">
    {!! Form::label('floors', 'Floors:') !!}
    {!! Form::number('floors', null, ['class' => 'form-control']) !!}
</div>

<!-- Floor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('floor', 'Floor:') !!}
    {!! Form::number('floor', null, ['class' => 'form-control']) !!}
</div>

<!-- County Field -->
<div class="form-group col-sm-6">
    {!! Form::label('county', 'County:') !!}
    {!! Form::text('county', null, ['class' => 'form-control']) !!}
</div>

<!-- Country Field -->
<div class="form-group col-sm-6">
    {!! Form::label('country', 'Country:') !!}
    {!! Form::text('country', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control']) !!}
</div>

<!-- Rent Per Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_month', 'Rent Per Month:') !!}
    {!! Form::number('rent_per_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Rent Per Week Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_week', 'Rent Per Week:') !!}
    {!! Form::number('rent_per_week', null, ['class' => 'form-control']) !!}
</div>

<!-- Rent Per Night Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rent_per_night', 'Rent Per Night:') !!}
    {!! Form::number('rent_per_night', null, ['class' => 'form-control']) !!}
</div>

<!-- Minimum Accepted Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('minimum_accepted_price', 'Minimum Accepted Price:') !!}
    {!! Form::number('minimum_accepted_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Minimum Accepted Price Short Term Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('minimum_accepted_price_short_term_price', 'Minimum Accepted Price Short Term Price:') !!}
    {!! Form::number('minimum_accepted_price_short_term_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Deposit Weeks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_deposit_weeks', 'Security Deposit Weeks:') !!}
    {!! Form::number('security_deposit_weeks', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_deposit_amount', 'Security Deposit Amount:') !!}
    {!! Form::number('security_deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Security Deposit Holding Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('security_deposit_holding_amount', 'Security Deposit Holding Amount:') !!}
    {!! Form::number('security_deposit_holding_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Contract Length Months Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contract_length_months', 'Contract Length Months:') !!}
    {!! Form::number('contract_length_months', null, ['class' => 'form-control']) !!}
</div>

<!-- Shortterm Rent Per Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shortterm_rent_per_month', 'Shortterm Rent Per Month:') !!}
    {!! Form::number('shortterm_rent_per_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Shortterm Rent Per Week Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shortterm_rent_per_week', 'Shortterm Rent Per Week:') !!}
    {!! Form::number('shortterm_rent_per_week', null, ['class' => 'form-control']) !!}
</div>

<!-- Valuation Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valuation_comment', 'Valuation Comment:') !!}
    {!! Form::text('valuation_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Valuation Rating Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valuation_rating', 'Valuation Rating:') !!}
    {!! Form::number('valuation_rating', null, ['class' => 'form-control']) !!}
</div>

<!-- Quick Booking Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quick_booking', 'Quick Booking:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('quick_booking', false) !!}
        {!! Form::checkbox('quick_booking', '1', null) !!}
    </label>
</div>

<!-- Area Overview Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_overview', 'Area Overview:') !!}
    {!! Form::text('area_overview', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Info Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_info', 'Area Info:') !!}
    {!! Form::text('area_info', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Rules Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rules', 'Rules:') !!}
    {!! Form::text('rules', null, ['class' => 'form-control']) !!}
</div>

<!-- Getting Around Field -->
<div class="form-group col-sm-6">
    {!! Form::label('getting_around', 'Getting Around:') !!}
    {!! Form::text('getting_around', null, ['class' => 'form-control']) !!}
</div>

<!-- Receptions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('receptions', 'Receptions:') !!}
    {!! Form::number('receptions', null, ['class' => 'form-control']) !!}
</div>

<!-- Bedrooms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bedrooms', 'Bedrooms:') !!}
    {!! Form::number('bedrooms', null, ['class' => 'form-control']) !!}
</div>

<!-- Bathrooms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bathrooms', 'Bathrooms:') !!}
    {!! Form::number('bathrooms', null, ['class' => 'form-control']) !!}
</div>

<!-- Has Garden Field -->
<div class="form-group col-sm-6">
    {!! Form::label('has_garden', 'Has Garden:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('has_garden', false) !!}
        {!! Form::checkbox('has_garden', '1', null) !!}
    </label>
</div>

<!-- Has Balcony Terrace Field -->
<div class="form-group col-sm-6">
    {!! Form::label('has_balcony_terrace', 'Has Balcony Terrace:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('has_balcony_terrace', false) !!}
        {!! Form::checkbox('has_balcony_terrace', '1', null) !!}
    </label>
</div>

<!-- Has Parking Field -->
<div class="form-group col-sm-6">
    {!! Form::label('has_parking', 'Has Parking:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('has_parking', false) !!}
        {!! Form::checkbox('has_parking', '1', null) !!}
    </label>
</div>

<!-- Ensuite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ensuite', 'Ensuite:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('ensuite', false) !!}
        {!! Form::checkbox('ensuite', '1', null) !!}
    </label>
</div>

<!-- Flatshares Field -->
<div class="form-group col-sm-6">
    {!! Form::label('flatshares', 'Flatshares:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('flatshares', false) !!}
        {!! Form::checkbox('flatshares', '1', null) !!}
    </label>
</div>

<!-- Reviewed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reviewed', 'Reviewed:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('reviewed', false) !!}
        {!! Form::checkbox('reviewed', '1', null) !!}
    </label>
</div>

<!-- Total Listing View Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total_listing_view', 'Total Listing View:') !!}
    {!! Form::number('total_listing_view', null, ['class' => 'form-control']) !!}
</div>

<!-- Total Detail View Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total_detail_view', 'Total Detail View:') !!}
    {!! Form::number('total_detail_view', null, ['class' => 'form-control']) !!}
</div>

<!-- View History Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('view_history', 'View History:') !!}
    {!! Form::textarea('view_history', null, ['class' => 'form-control']) !!}
</div>

<!-- Extra Info Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('extra_info', 'Extra Info:') !!}
    {!! Form::textarea('extra_info', null, ['class' => 'form-control']) !!}
</div>

<!-- Inclusive Field -->
<div class="form-group col-sm-6">
    {!! Form::label('inclusive', 'Inclusive:') !!}
    {!! Form::text('inclusive', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('properties.index') !!}" class="btn btn-default">Cancel</a>
</div>
