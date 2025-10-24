<div class="form-group @if ($errors->has('first_name')) has-error @endif">
    <label for="first_name" class="col-sm-2 control-label">{{ trans('users.first_name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', isset($user) ? $user->first_name : null) }}" class="form-control">
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
    <label for="last_name" class="col-sm-2 control-label">{{ trans('users.last_name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', isset($user) ? $user->last_name : null) }}" class="form-control">
        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="email" class="col-sm-2 control-label">{{ trans('misc.email') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="email" id="email" value="{{ old('email', isset($user) ? $user->email : null) }}" class="form-control">
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('password')) has-error @endif">
    <label for="password" class="col-sm-2 control-label">{{ trans('profile.password') }}:</label>
    <div class="col-sm-10">
        <input type="password" name="password" id="password" class="form-control">
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
    <label for="password_confirmation" class="col-sm-2 control-label">{{ trans('profile.password_confirmation') }}:</label>
    <div class="col-sm-10">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('locale')) has-error @endif">
    <label for="locale" class="col-sm-2 control-label">{{ trans('misc.language') }}:</label>
    <div class="col-sm-10">
        <select name="locale" id="locale" class="form-control">
            @foreach($locale_selection as $code => $label)
                <option value="{{ $code }}" @if(old('locale', $locale_selected) == $code) selected @endif>{{ $label }}</option>
            @endforeach
        </select>
        @if ($errors->has('locale')) <p class="help-block">{{ $errors->first('locale') }}</p> @endif
    </div>
</div>
@if ($auth_user->account->isSystemAccount())
<div class="form-group @if ($errors->has('account_id')) has-error @endif">
    <label for="account_id" class="col-sm-2 control-label">{{ trans_choice('misc.accounts', 1) }}:</label>
    <div class="col-sm-10">
        <select name="account_id" id="account_id" class="form-control">
            @foreach($account_selection as $id => $name)
                <option value="{{ $id }}" @if(old('account_id', $selected) == $id) selected @endif>{{ $name }}</option>
            @endforeach
        </select>
        @if ($errors->has('account_id')) <p class="help-block">{{ $errors->first('account_id') }}</p> @endif
    </div>
</div>
@else
<input type="hidden" name="account_id" value="{{ $auth_user->account->id }}">
@endif
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    <label for="roles" class="col-sm-2 control-label">{{ trans_choice('misc.roles', 2) }}:</label>
    <div class="col-sm-10">
        <select name="roles[]" id="roles" class="form-control" multiple="multiple">
            @foreach($roles as $roleId => $roleName)
                <option value="{{ $roleId }}" @if(in_array($roleId, old('roles', $selected_roles ?? []))) selected @endif>{{ $roleName }}</option>
            @endforeach
        </select>
        @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('disable')) has-error @endif">
    <label for="disabled" class="col-sm-2 control-label">{{ trans('misc.disabled') }}:</label>
    <div class="col-sm-10">
        <input type="hidden" name="disabled" id="disabled" value="false">
        <input type="checkbox" name="disableddummy" value="1" @if($disabled_checked) checked @endif>
        @if ($errors->has('disabled')) <p class="help-block">{{ $errors->first('disabled') }}</p> @endif
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
    </div>
</div>

@section('extrajs')
    <script>
        $('input:checkbox[name="disableddummy"]').change(function() {
            $('#disabled').val($(this).is(':checked'));
        });
    </script>
@stop