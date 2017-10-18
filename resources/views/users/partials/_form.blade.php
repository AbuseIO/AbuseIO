<div class="row">
    <div class="col-sm-6 form-group label-placeholder @if ($errors->has('first_name')) has-error @endif">
        {!! Form::label('first_name', trans('users.first_name'), ['class' => 'control-label']) !!}
        {!! Form::text('first_name', null, ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
    <div class="col-sm-6 form-group label-placeholder @if ($errors->has('last_name')) has-error @endif">
        {!! Form::label('last_name', trans('users.last_name'), ['class' => 'control-label']) !!}
        {!! Form::text('last_name', null, ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>
<div class="form-group label-placeholder @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('misc.email'), ['class' => 'control-label']) !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>
<div class="row">
    <div class="col-sm-6 form-group label-placeholder @if ($errors->has('password')) has-error @endif">
        {!! Form::label('password', trans('profile.password'), ['class' => 'control-label']) !!}
        {!! Form::password('password', ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
    <div class="col-sm-6 form-group label-placeholder @if ($errors->has('password_confirmation')) has-error @endif">
        {!! Form::label('password_confirmation', trans('profile.password_confirmation'), ['class' => 'control-label']) !!}
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>
</div>
<div class="row" id="dropdown-menu">
    <div class="col-sm-4 form-group @if ($errors->has('locale')) has-error @endif">
        {!! Form::label('locale', ucfirst(trans_choice('misc.language', 1))) !!}
        {!! Form::select('locale', $locale_selection, $locale_selected, ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('locale')) <p class="help-block">{{ $errors->first('locale') }}</p> @endif
    </div>
    @if ($auth_user->account->isSystemAccount())
    <div class="col-sm-4 form-group @if ($errors->has('account_id')) has-error @endif">
        {!! Form::label('account_id', ucfirst(trans_choice('misc.account', 1))) !!}
        {!! Form::select('account_id', $account_selection, $selected, ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('account_id')) <p class="help-block">{{ $errors->first('account_id') }}</p> @endif
    </div>
    @else
        {!! Form::hidden('account_id', $auth_user->account->id) !!}
    @endif
    <div class="col-sm-4 form-group @if ($errors->has('roles')) has-error @endif">
        {!! Form::label('roles', trans_choice('misc.role', 2)) !!}
        {!! Form::select('roles[]', $roles, $selected_roles, ['class' => 'form-control', 'multiple' => 'multiple', 'data-lpignore' => 'true']) !!}
        @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
    </div>
</div>
<div class="form-group label-floating @if ($errors->has('disable')) has-error @endif">
    {!! Form::label('disabled', trans('misc.disabled')) !!}
    {!! Form::hidden('disabled', 'false') !!}
    <div class="checkbox">
        <label>
            {!! Form::checkbox('disableddummy', true, $disabled_checked, ['data-lpignore' => 'true']) !!}
        </label>
    </div>
    @if ($errors->has('disabled')) <p class="help-block">{{ $errors->first('disabled') }}</p> @endif
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class'=>'btn btn-raised btn-success']) !!}
    {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-raised btn-default']) !!}
</div>
@section('extrajs')
    <script>
        $('input:checkbox[name="disableddummy"]').change(function() {
            $('#disabled').val($(this).is(':checked'));
        });
        $('#dropdown-menu').find('select').dropdown();
    </script>
@stop