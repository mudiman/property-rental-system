<table class="table table-responsive" id="paymentMethods-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Is Active</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($paymentMethods as $paymentMethod)
        <tr>
            <td>{!! $paymentMethod->id !!}</td>
            <td>{!! $paymentMethod->title !!}</td>
            <td>{!! $paymentMethod->is_active !!}</td>
            <td>
                {!! Form::open(['route' => ['paymentMethods.destroy', $paymentMethod->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('paymentMethods.show', [$paymentMethod->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('paymentMethods.edit', [$paymentMethod->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>