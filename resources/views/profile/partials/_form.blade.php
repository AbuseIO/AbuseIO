<div class="form-group @if ($errors->has('first_name')) has-error @endif">
    {!! Form::label('first_name', trans('accounts.first_name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
    {!! Form::label('last_name', trans('accounts.last_name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
       {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
       @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('misc.email').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('password')) has-error @endif">
    {!! Form::label('password', trans('profile.password').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::password('password', ['class' => 'form-control']) !!}
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
    {!! Form::label('password_confirmation', trans('profile.password_confirmation').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
