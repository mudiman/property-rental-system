<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $property->id !!}</p>
</div>

<!-- Landlord Id Field -->
<div class="form-group">
    {!! Form::label('landlord_id', 'Landlord Id:') !!}
    <p>{!! $property->landlord_id !!}</p>
</div>

<!-- Profile Picture Field -->
<div class="form-group">
    {!! Form::label('profile_picture', 'Profile Picture:') !!}
    <p>{!! $property->profile_picture !!}</p>
</div>

<!-- Reference Field -->
<div class="form-group">
    {!! Form::label('reference', 'Reference:') !!}
    <p>{!! $property->reference !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $property->title !!}</p>
</div>

<!-- Summary Field -->
<div class="form-group">
    {!! Form::label('summary', 'Summary:') !!}
    <p>{!! $property->summary !!}</p>
</div>

<!-- Letting Type Field -->
<div class="form-group">
    {!! Form::label('letting_type', 'Letting Type:') !!}
    <p>{!! $property->letting_type !!}</p>
</div>

<!-- Property Type Field -->
<div class="form-group">
    {!! Form::label('property_type', 'Property Type:') !!}
    <p>{!! $property->property_type !!}</p>
</div>

<!-- Room Type Field -->
<div class="form-group">
    {!! Form::label('room_type', 'Room Type:') !!}
    <p>{!! $property->room_type !!}</p>
</div>

<!-- Room Suitable Field -->
<div class="form-group">
    {!! Form::label('room_suitable', 'Room Suitable:') !!}
    <p>{!! $property->room_suitable !!}</p>
</div>

<!-- Bathroom Type Field -->
<div class="form-group">
    {!! Form::label('bathroom_type', 'Bathroom Type:') !!}
    <p>{!! $property->bathroom_type !!}</p>
</div>

<!-- People Living Field -->
<div class="form-group">
    {!! Form::label('people_living', 'People Living:') !!}
    <p>{!! $property->people_living !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $property->status !!}</p>
</div>

<!-- Completion Phase Field -->
<div class="form-group">
    {!! Form::label('completion_phase', 'Completion Phase:') !!}
    <p>{!! $property->completion_phase !!}</p>
</div>

<!-- Available Date Field -->
<div class="form-group">
    {!! Form::label('available_date', 'Available Date:') !!}
    <p>{!! $property->available_date !!}</p>
</div>

<!-- Cordinate Field -->
<div class="form-group">
    {!! Form::label('cordinate', 'Cordinate:') !!}
    <p>{!! isset($property->cordinate) ? $property->cordinate['lat'].",".$property->cordinate['lon']: null !!}</p>
</div>

<!-- Postcode Field -->
<div class="form-group">
    {!! Form::label('postcode', 'Postcode:') !!}
    <p>{!! $property->postcode !!}</p>
</div>

<!-- Door Number Field -->
<div class="form-group">
    {!! Form::label('door_number', 'Door Number:') !!}
    <p>{!! $property->door_number !!}</p>
</div>

<!-- Street Field -->
<div class="form-group">
    {!! Form::label('street', 'Street:') !!}
    <p>{!! $property->street !!}</p>
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', 'City:') !!}
    <p>{!! $property->city !!}</p>
</div>

<!-- Verified Field -->
<div class="form-group">
    {!! Form::label('verified', 'Verified:') !!}
    <p>{!! $property->verified !!}</p>
</div>

<!-- Apartment Building Field -->
<div class="form-group">
    {!! Form::label('apartment_building', 'Apartment Building:') !!}
    <p>{!! $property->apartment_building !!}</p>
</div>

<!-- Floors Field -->
<div class="form-group">
    {!! Form::label('floors', 'Floors:') !!}
    <p>{!! $property->floors !!}</p>
</div>

<!-- Floor Field -->
<div class="form-group">
    {!! Form::label('floor', 'Floor:') !!}
    <p>{!! $property->floor !!}</p>
</div>

<!-- County Field -->
<div class="form-group">
    {!! Form::label('county', 'County:') !!}
    <p>{!! $property->county !!}</p>
</div>

<!-- Country Field -->
<div class="form-group">
    {!! Form::label('country', 'Country:') !!}
    <p>{!! $property->country !!}</p>
</div>

<!-- Currency Field -->
<div class="form-group">
    {!! Form::label('currency', 'Currency:') !!}
    <p>{!! $property->currency !!}</p>
</div>

<!-- Rent Per Month Field -->
<div class="form-group">
    {!! Form::label('rent_per_month', 'Rent Per Month:') !!}
    <p>{!! $property->rent_per_month !!}</p>
</div>

<!-- Rent Per Week Field -->
<div class="form-group">
    {!! Form::label('rent_per_week', 'Rent Per Week:') !!}
    <p>{!! $property->rent_per_week !!}</p>
</div>

<!-- Rent Per Night Field -->
<div class="form-group">
    {!! Form::label('rent_per_night', 'Rent Per Night:') !!}
    <p>{!! $property->rent_per_night !!}</p>
</div>

<!-- Minimum Accepted Price Field -->
<div class="form-group">
    {!! Form::label('minimum_accepted_price', 'Minimum Accepted Price:') !!}
    <p>{!! $property->minimum_accepted_price !!}</p>
</div>

<!-- Minimum Accepted Price Short Term Price Field -->
<div class="form-group">
    {!! Form::label('minimum_accepted_price_short_term_price', 'Minimum Accepted Price Short Term Price:') !!}
    <p>{!! $property->minimum_accepted_price_short_term_price !!}</p>
</div>

<!-- Security Deposit Weeks Field -->
<div class="form-group">
    {!! Form::label('security_deposit_weeks', 'Security Deposit Weeks:') !!}
    <p>{!! $property->security_deposit_weeks !!}</p>
</div>

<!-- Security Deposit Amount Field -->
<div class="form-group">
    {!! Form::label('security_deposit_amount', 'Security Deposit Amount:') !!}
    <p>{!! $property->security_deposit_amount !!}</p>
</div>

<!-- Security Deposit Holding Amount Field -->
<div class="form-group">
    {!! Form::label('security_deposit_holding_amount', 'Security Deposit Holding Amount:') !!}
    <p>{!! $property->security_deposit_holding_amount !!}</p>
</div>

<!-- Contract Length Months Field -->
<div class="form-group">
    {!! Form::label('contract_length_months', 'Contract Length Months:') !!}
    <p>{!! $property->contract_length_months !!}</p>
</div>

<!-- Shortterm Rent Per Month Field -->
<div class="form-group">
    {!! Form::label('shortterm_rent_per_month', 'Shortterm Rent Per Month:') !!}
    <p>{!! $property->shortterm_rent_per_month !!}</p>
</div>

<!-- Shortterm Rent Per Week Field -->
<div class="form-group">
    {!! Form::label('shortterm_rent_per_week', 'Shortterm Rent Per Week:') !!}
    <p>{!! $property->shortterm_rent_per_week !!}</p>
</div>

<!-- Valuation Comment Field -->
<div class="form-group">
    {!! Form::label('valuation_comment', 'Valuation Comment:') !!}
    <p>{!! $property->valuation_comment !!}</p>
</div>

<!-- Valuation Rating Field -->
<div class="form-group">
    {!! Form::label('valuation_rating', 'Valuation Rating:') !!}
    <p>{!! $property->valuation_rating !!}</p>
</div>

<!-- Quick Booking Field -->
<div class="form-group">
    {!! Form::label('quick_booking', 'Quick Booking:') !!}
    <p>{!! $property->quick_booking !!}</p>
</div>

<!-- Area Overview Field -->
<div class="form-group">
    {!! Form::label('area_overview', 'Area Overview:') !!}
    <p>{!! $property->area_overview !!}</p>
</div>

<!-- Area Info Field -->
<div class="form-group">
    {!! Form::label('area_info', 'Area Info:') !!}
    <p>{!! $property->area_info !!}</p>
</div>

<!-- Notes Field -->
<div class="form-group">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{!! $property->notes !!}</p>
</div>

<!-- Rules Field -->
<div class="form-group">
    {!! Form::label('rules', 'Rules:') !!}
    <p>{!! $property->rules !!}</p>
</div>

<!-- Getting Around Field -->
<div class="form-group">
    {!! Form::label('getting_around', 'Getting Around:') !!}
    <p>{!! $property->getting_around !!}</p>
</div>

<!-- Receptions Field -->
<div class="form-group">
    {!! Form::label('receptions', 'Receptions:') !!}
    <p>{!! $property->receptions !!}</p>
</div>

<!-- Bedrooms Field -->
<div class="form-group">
    {!! Form::label('bedrooms', 'Bedrooms:') !!}
    <p>{!! $property->bedrooms !!}</p>
</div>

<!-- Bathrooms Field -->
<div class="form-group">
    {!! Form::label('bathrooms', 'Bathrooms:') !!}
    <p>{!! $property->bathrooms !!}</p>
</div>

<!-- Has Garden Field -->
<div class="form-group">
    {!! Form::label('has_garden', 'Has Garden:') !!}
    <p>{!! $property->has_garden !!}</p>
</div>

<!-- Has Balcony Terrace Field -->
<div class="form-group">
    {!! Form::label('has_balcony_terrace', 'Has Balcony Terrace:') !!}
    <p>{!! $property->has_balcony_terrace !!}</p>
</div>

<!-- Has Parking Field -->
<div class="form-group">
    {!! Form::label('has_parking', 'Has Parking:') !!}
    <p>{!! $property->has_parking !!}</p>
</div>

<!-- Ensuite Field -->
<div class="form-group">
    {!! Form::label('ensuite', 'Ensuite:') !!}
    <p>{!! $property->ensuite !!}</p>
</div>

<!-- Flatshares Field -->
<div class="form-group">
    {!! Form::label('flatshares', 'Flatshares:') !!}
    <p>{!! $property->flatshares !!}</p>
</div>

<!-- Reviewed Field -->
<div class="form-group">
    {!! Form::label('reviewed', 'Reviewed:') !!}
    <p>{!! $property->reviewed !!}</p>
</div>

<!-- Total Listing View Field -->
<div class="form-group">
    {!! Form::label('total_listing_view', 'Total Listing View:') !!}
    <p>{!! $property->total_listing_view !!}</p>
</div>

<!-- Total Detail View Field -->
<div class="form-group">
    {!! Form::label('total_detail_view', 'Total Detail View:') !!}
    <p>{!! $property->total_detail_view !!}</p>
</div>

<!-- View History Field -->
<div class="form-group">
    {!! Form::label('view_history', 'View History:') !!}
    <p>{!! $property->view_history !!}</p>
</div>

<!-- Extra Info Field -->
<div class="form-group">
    {!! Form::label('extra_info', 'Extra Info:') !!}
    <p>{!! $property->extra_info !!}</p>
</div>

<!-- Inclusive Field -->
<div class="form-group">
    {!! Form::label('inclusive', 'Inclusive:') !!}
    <p>{!! $property->inclusive !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $property->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $property->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $property->updated_at !!}</p>
</div>

<!-- Updated By Field -->
<div class="form-group">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{!! $property->updated_by !!}</p>
</div>

<!-- Created By Field -->
<div class="form-group">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{!! $property->created_by !!}</p>
</div>

<!-- Deleted By Field -->
<div class="form-group">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{!! $property->deleted_by !!}</p>
</div>
