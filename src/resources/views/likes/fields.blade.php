<!-- Likeable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('likeable_id', 'Likeable Id:') !!}
    {!! Form::number('likeable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Likeable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('likeable_type', 'Likeable Type:') !!}
    {!! Form::select('likeable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()))) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('likes.index') !!}" class="btn btn-default">Cancel</a>
</div>
