<!-- Score Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_type_id', 'Score Type Id:') !!}
    {!! Form::number('score_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Scoreable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('scoreable_id', 'Scoreable Id:') !!}
    {!! Form::number('scoreable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Scoreable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('scoreable_type', 'Scoreable Type:') !!}
    {!! Form::select('scoreable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()))) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Score Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score', 'Score:') !!}
    {!! Form::number('score', null, ['class' => 'form-control']) !!}
</div>

<!-- Score Change Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_change', 'Score Change:') !!}
    {!! Form::number('score_change', null, ['class' => 'form-control']) !!}
</div>

<!-- Current Field -->
<div class="form-group col-sm-6">
    {!! Form::label('current', 'Current:') !!}
    {!! Form::number('current', null, ['class' => 'form-control']) !!}
</div>

<!-- Max Field -->
<div class="form-group col-sm-6">
    {!! Form::label('max', 'Max:') !!}
    {!! Form::number('max', null, ['class' => 'form-control']) !!}
</div>

<!-- Min Field -->
<div class="form-group col-sm-6">
    {!! Form::label('min', 'Min:') !!}
    {!! Form::number('min', null, ['class' => 'form-control']) !!}
</div>

<!-- Factor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('factor', 'Factor:') !!}
    {!! Form::number('factor', null, ['class' => 'form-control']) !!}
</div>

<!-- Streak Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('streak_count', 'Streak Count:') !!}
    {!! Form::number('streak_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Max Diff Field -->
<div class="form-group col-sm-6">
    {!! Form::label('max_diff', 'Max Diff:') !!}
    {!! Form::number('max_diff', null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('scores.index') !!}" class="btn btn-default">Cancel</a>
</div>
