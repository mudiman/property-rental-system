<table class="table table-responsive" id="reviews-table">
    <thead>
        <th>By User</th>
        <th>For User</th>
        <th>Score Type Id</th>
        <th>Comment</th>
        <th>Rating</th>
        <th>Punctuality</th>
        <th>Quality</th>
        <th>Reviewable Id</th>
        <th>Reviewable Type</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($reviews as $review)
        <tr>
            <td>{!! $review->by_user !!}</td>
            <td>{!! $review->for_user !!}</td>
            <td>{!! $review->score_type_id !!}</td>
            <td>{!! $review->comment !!}</td>
            <td>{!! $review->rating !!}</td>
            <td>{!! $review->punctuality !!}</td>
            <td>{!! $review->quality !!}</td>
            <td>{!! $review->reviewable_id !!}</td>
            <td>{!! $review->reviewable_type !!}</td>
            <td>{!! $review->updated_by !!}</td>
            <td>{!! $review->created_by !!}</td>
            <td>{!! $review->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['reviews.destroy', $review->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('reviews.show', [$review->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('reviews.edit', [$review->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>