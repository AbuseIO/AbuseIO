<div class="form-group @if ($errors->has('first_name')) has-error @endif">
    {!! Form::label('first_name', trans('users.first_name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
    {!! Form::label('last_name', trans('users.last_name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('misc.email').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
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
<div class="form-group @if ($errors->has('locale')) has-error @endif">
    {!! Form::label('locale', trans('misc.language').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('locale', $locale_selection, $locale_selected, ['class' => 'form-control']) !!}
        @if ($errors->has('locale')) <p class="help-block">{{ $errors->first('locale') }}</p> @endif
    </div>
</div>
@if ($auth_user->account->isSystemAccount())
<div class="form-group @if ($errors->has('account_id')) has-error @endif">
    {!! Form::label('account_id', trans_choice('misc.accounts', 1).':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('account_id', $account_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('account_id')) <p class="help-block">{{ $errors->first('account_id') }}</p> @endif
    </div>
</div>
@else
{!! Form::hidden('account_id', $auth_user->account->id) !!}
@endif
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    {!! Form::label('roles', trans_choice('misc.roles', 2).':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('roles[]',
        $roles,
        $selected_roles,
        ['class' => 'form-control',
        'multiple' => 'multiple']) !!}
        @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('disable')) has-error @endif">
    {!! Form::label('disabled', trans('misc.disabled').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::hidden('disabled', 'false') !!}
        {!! Form::checkbox('disableddummy', true, $disabled_checked) !!}
        @if ($errors->has('disabled')) <p class="help-block">{{ $errors->first('disabled') }}</p> @endif
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>

@section('extrajs')
    <script>
        $('input:checkbox[name="disableddummy"]').change(function() {
            $('#disabled').val($(this).is(':checked'));
        });
    </script>
@stop