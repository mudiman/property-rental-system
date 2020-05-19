<table class="table table-responsive" id="scores-table">
    <thead>
        <th>Score Type Id</th>
        <th>User Id</th>
        <th>Scoreable Id</th>
        <th>Scoreable Type</th>
        <th>Status</th>
        <th>Score</th>
        <th>Score Change</th>
        <th>Current</th>
        <th>Max</th>
        <th>Min</th>
        <th>Factor</th>
        <th>Streak Count</th>
        <th>Max Diff</th>
        <th>Comment</th>
        <th>Updated By</th>
        <th>Created By</th>
        <th>Deleted By</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($scores as $score)
        <tr>
            <td>{!! $score->score_type_id !!}</td>
            <td>{!! $score->user_id !!}</td>
            <td>{!! $score->scoreable_id !!}</td>
            <td>{!! $score->scoreable_type !!}</td>
            <td>{!! $score->status !!}</td>
            <td>{!! $score->score !!}</td>
            <td>{!! $score->score_change !!}</td>
            <td>{!! $score->current !!}</td>
            <td>{!! $score->max !!}</td>
            <td>{!! $score->min !!}</td>
            <td>{!! $score->factor !!}</td>
            <td>{!! $score->streak_count !!}</td>
            <td>{!! $score->max_diff !!}</td>
            <td>{!! $score->comment !!}</td>
            <td>{!! $score->updated_by !!}</td>
            <td>{!! $score->created_by !!}</td>
            <td>{!! $score->deleted_by !!}</td>
            <td>
                {!! Form::open(['route' => ['scores.destroy', $score->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('scores.show', [$score->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('scores.edit', [$score->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>