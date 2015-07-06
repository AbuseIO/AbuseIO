<div class="form-group @if ($errors->has('first_ip')) has-error @endif">
    {!! Form::label('first_ip', 'First IP address:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('first_ip', null, ['class' => 'form-control', 'placeholder'=> '0.0.0.0']) !!}
        @if ($errors->has('first_ip')) <p class="help-block">{{ $errors->first('first_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_ip')) has-error @endif">
    {!! Form::label('last_ip', 'Last IP address:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('last_ip', null, ['class' => 'form-control', 'placeholder'=> '0.0.0.0']) !!}
        @if ($errors->has('last_ip')) <p class="help-block">{{ $errors->first('last_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('contact_id')) has-error @endif">
    {!! Form::label('contact_id', 'Contact:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('contact_id', $contact_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('contact_id')) <p class="help-block">{{ $errors->first('contact_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
    </div>
</div>
