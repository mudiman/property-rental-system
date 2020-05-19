<table class="table table-responsive" id="userServices-table">
    <thead>
        <th>User Id</th>
        <th>Service Id</th>
        <th>Pricing</th>
        <th>Price</th>
        <th>Extra Charges</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($userServices as $userService)
        <tr>
            <td>{!! $userService->user_id !!}</td>
            <td>{!! $userService->service_id !!}</td>
            <td>{!! $userService->pricing !!}</td>
            <td>{!! $userService->price !!}</td>
            <td>{!! $userService->extra_charges !!}</td>
            <td>
                {!! Form::open(['route' => ['userServices.destroy', $userService->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('userServices.show', [$userService->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('userServices.edit', [$userService->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>