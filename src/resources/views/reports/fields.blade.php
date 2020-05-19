<!-- By User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('by_user', 'By User:') !!}
    {!! Form::number('by_user', isset($report->by_user) ? $report->by_user: Auth::user()->id, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Reportable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reportable_id', 'Reportable Id:') !!}
    {!! Form::number('reportable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Reportable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reportable_type', 'Reportable Type:') !!}
    {!! Form::select('reportable_type', array_combine(array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()), array_keys(\Illuminate\Database\Eloquent\Relations\Relation::morphMap()))) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('reports.index') !!}" class="btn btn-default">Cancel</a>
</div>
