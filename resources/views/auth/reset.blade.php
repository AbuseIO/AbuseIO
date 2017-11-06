@extends('auth.app')

@section('content')
<div class="row">
    <div class="col-md-5 col-centered top-buffer">
        <div class="card">
            <div class="card-header bg-primary">
                <div class="text-center">
                    <img src="{{ asset('images/logo_150.png') }}" alt="{{ Config::get('app.name') }}"/>
                </div>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ trans('login.reset_password') }}</h4>
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <form role="form" method="POST" action="{{ url('/password/reset') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="email">{{ trans('login.email_address') }}</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))<small class="help-block text-danger">{{ $errors->first('email') }}</small>@endif
                    </div>
                    <div class="form-group">
                        <label for="password">{{ trans('login.password') }}</label>
                        <input type="password" class="form-control" name="password" id="password">
                        @if ($errors->has('password'))<small class="help-block text-danger">{{ $errors->first('password') }}</small>@endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ trans('login.password_confirmation') }}</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                        @if ($errors->has('password_confirmation'))<small class="help-block text-danger">{{ $errors->first('password_confirmation') }}</small>@endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-raised btn-primary">{{ trans('login.reset_password') }}</button>
                        <a href="{{ url('/auth/login') }}" class="btn btn-link">{{ trans('misc.button.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
