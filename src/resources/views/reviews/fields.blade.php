<!-- By User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('by_user', 'By User:') !!}
    {!! Form::number('by_user', isset($review->by_user) ? $review->by_user: Auth::user()->id, ['class' => 'form-control']) !!}
</div>

<!-- For User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('for_user', 'For User:') !!}
    {!! Form::number('for_user', null, ['class' => 'form-control']) !!}
</div>

<!-- Score Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_type_id', 'Score Type Id:') !!}
    {!! Form::number('score_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Rating Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rating', 'Rating:') !!}
    {!! Form::number('rating', null, ['class' => 'form-control']) !!}
</div>

<!-- Punctuality Field -->
<div class="form-group col-sm-6">
    {!! Form::label('punctuality', 'Punctuality:') !!}
    {!! Form::number('punctuality', null, ['class' => 'form-control']) !!}
</div>

<!-- Quality Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quality', 'Quality:') !!}
    {!! Form::number('quality', null, ['class' => 'form-control']) !!}
</div>

<!-- Reviewable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reviewable_id', 'Reviewable Id:') !!}
    {!! Form::number('reviewable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Reviewable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reviewable_type', 'Reviewable Type:') !!}
    {!! Form::select('reviewable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()))) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('reviews.index') !!}" class="btn btn-default">Cancel</a>
</div>
