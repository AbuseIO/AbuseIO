<div class="form-group @if ($errors->has('first_name')) has-error @endif">
    <label for="first_name" class="col-sm-2 control-label">{{ trans('accounts.first_name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $auth_user->first_name) }}" class="form-control">
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_name')) has-error @endif">
    <label for="last_name" class="col-sm-2 control-label">{{ trans('accounts.last_name') }}:</label>
    <div class="col-sm-10">
       <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $auth_user->last_name) }}" class="form-control">
       @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="email" class="col-sm-2 control-label">{{ trans('misc.email') }}:</label>
    <div class="col-sm-10">
        <input type="email" name="email" id="email" value="{{ old('email', $auth_user->email) }}" class="form-control">
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
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ route('admin.home') }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
    </div>
</div>
