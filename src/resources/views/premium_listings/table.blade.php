<table class="table table-responsive" id="premiumListings-table">
    <thead>
        <th>Type</th>
        <th>Property Id</th>
        <th>End Datetime</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($premiumListings as $premiumListing)
        <tr>
            <td>{!! $premiumListing->type !!}</td>
            <td>{!! $premiumListing->property_id !!}</td>
            <td>{!! $premiumListing->end_datetime !!}</td>
            <td>
                {!! Form::open(['route' => ['premiumListings.destroy', $premiumListing->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('premiumListings.show', [$premiumListing->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('premiumListings.edit', [$premiumListing->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>