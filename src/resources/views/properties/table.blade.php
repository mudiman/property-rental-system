<table class="table table-responsive" id="properties-table">
    <thead>
        <th>Landlord Id</th>
        <th>Profile Picture</th>
        <th>Reference</th>
        <th>Title</th>
        <th>Summary</th>
        <th>Letting Type</th>
        <th>Property Type</th>
        <th>Room Type</th>
        <th>Room Suitable</th>
        <th>Bathroom Type</th>
        <th>People Living</th>
        <th>Status</th>
        <th>Completion Phase</th>
        <th>Available Date</th>
        <th>Cordinate</th>
        <th>Postcode</th>
        <th>Door Number</th>
        <th>Street</th>
        <th>City</th>
        <th>Verified</th>
        <th>Apartment Building</th>
        <th>Floors</th>
        <th>Floor</th>
        <th>County</th>
        <th>Country</th>
        <th>Currency</th>
        <th>Rent Per Month</th>
        <th>Rent Per Week</th>
        <th>Rent Per Night</th>
        <th>Minimum Accepted Price</th>
        <th>Minimum Accepted Price Short Term Price</th>
        <th>Security Deposit Weeks</th>
        <th>Security Deposit Amount</th>
        <th>Security Deposit Holding Amount</th>
        <th>Contract Length Months</th>
        <th>Shortterm Rent Per Month</th>
        <th>Shortterm Rent Per Week</th>
        <th>Valuation Comment</th>
        <th>Valuation Rating</th>
        <th>Quick Booking</th>
        <th>Area Overview</th>
        <th>Area Info</th>
        <th>Notes</th>
        <th>Rules</th>
        <th>Getting Around</th>
        <th>Receptions</th>
        <th>Bedrooms</th>
        <th>Bathrooms</th>
        <th>Has Garden</th>
        <th>Has Balcony Terrace</th>
        <th>Has Parking</th>
        <th>Ensuite</th>
        <th>Flatshares</th>
        <th>Reviewed</th>
        <th>Total Listing View</th>
        <th>Total Detail View</th>
        <th>View History</th>
        <th>Extra Info</th>
        <th>Inclusive</th>
        <th>Parent Property Id</th>
        <th>Data</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($properties as $property)
        <tr>
            <td>{!! $property->landlord_id !!}</td>
            <td>{!! $property->profile_picture !!}</td>
            <td>{!! $property->reference !!}</td>
            <td>{!! $property->title !!}</td>
            <td>{!! $property->summary !!}</td>
            <td>{!! $property->letting_type !!}</td>
            <td>{!! $property->property_type !!}</td>
            <td>{!! $property->room_type !!}</td>
            <td>{!! $property->room_suitable !!}</td>
            <td>{!! $property->bathroom_type !!}</td>
            <td>{!! $property->people_living !!}</td>
            <td>{!! $property->status !!}</td>
            <td>{!! $property->completion_phase !!}</td>
            <td>{!! $property->available_date !!}</td>
            <td>{!! $property->cordinate !!}</td>
            <td>{!! $property->postcode !!}</td>
            <td>{!! $property->door_number !!}</td>
            <td>{!! $property->street !!}</td>
            <td>{!! $property->city !!}</td>
            <td>{!! $property->verified !!}</td>
            <td>{!! $property->apartment_building !!}</td>
            <td>{!! $property->floors !!}</td>
            <td>{!! $property->floor !!}</td>
            <td>{!! $property->county !!}</td>
            <td>{!! $property->country !!}</td>
            <td>{!! $property->currency !!}</td>
            <td>{!! $property->rent_per_month !!}</td>
            <td>{!! $property->rent_per_week !!}</td>
            <td>{!! $property->rent_per_night !!}</td>
            <td>{!! $property->minimum_accepted_price !!}</td>
            <td>{!! $property->minimum_accepted_price_short_term_price !!}</td>
            <td>{!! $property->security_deposit_weeks !!}</td>
            <td>{!! $property->security_deposit_amount !!}</td>
            <td>{!! $property->security_deposit_holding_amount !!}</td>
            <td>{!! $property->contract_length_months !!}</td>
            <td>{!! $property->shortterm_rent_per_month !!}</td>
            <td>{!! $property->shortterm_rent_per_week !!}</td>
            <td>{!! $property->valuation_comment !!}</td>
            <td>{!! $property->valuation_rating !!}</td>
            <td>{!! $property->quick_booking !!}</td>
            <td>{!! $property->area_overview !!}</td>
            <td>{!! $property->area_info !!}</td>
            <td>{!! $property->notes !!}</td>
            <td>{!! $property->rules !!}</td>
            <td>{!! $property->getting_around !!}</td>
            <td>{!! $property->receptions !!}</td>
            <td>{!! $property->bedrooms !!}</td>
            <td>{!! $property->bathrooms !!}</td>
            <td>{!! $property->has_garden !!}</td>
            <td>{!! $property->has_balcony_terrace !!}</td>
            <td>{!! $property->has_parking !!}</td>
            <td>{!! $property->ensuite !!}</td>
            <td>{!! $property->flatshares !!}</td>
            <td>{!! $property->reviewed !!}</td>
            <td>{!! $property->total_listing_view !!}</td>
            <td>{!! $property->total_detail_view !!}</td>
            <td>{!! $property->view_history !!}</td>
            <td>{!! $property->extra_info !!}</td>
            <td>{!! $property->inclusive !!}</td>
            <td>{!! $property->parent_property_id !!}</td>
            <td>{!! $property->data !!}</td>
            <td>{!! $property->updated_by !!}</td>
            <td>{!! $property->created_by !!}</td>
            <td>{!! $property->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['properties.destroy', $property->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('properties.show', [$property->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('properties.edit', [$property->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>