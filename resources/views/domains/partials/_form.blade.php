<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Domain name:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('contact_id')) has-error @endif">
    {!! Form::label('contact_id', 'Contact:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('contact_id', $contact_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('contact_id')) <p class="help-block">{{ $errors->first('contact_id') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
    </div>
</div>
