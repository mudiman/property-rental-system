<!-- By User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('by_user', 'By User:') !!}
    {!! Form::number('by_user', isset($reference->by_user) ? $reference->by_user: Auth::user()->id, ['class' => 'form-control']) !!}
</div>

<!-- For User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('for_user', 'For User:') !!}
    {!! Form::number('for_user', null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('references.index') !!}" class="btn btn-default">Cancel</a>
</div>
