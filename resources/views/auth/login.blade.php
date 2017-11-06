@extends('auth.app')

@section('content')
<div class="row">
    <div class="col-md-5 col-centered top-buffer">
        <div class="card">
            <div class="card-header bg-abuseio">
                <div class="text-center">
                    <img src="{{ asset('images/logo_150.png') }}" alt="{{ Config::get('app.name') }}"/>
                </div>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ trans('login.login') }}</h4>
                <form method="POST" action="{{ url('/auth/login') }}">
                    {{ csrf_field() }}
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
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> {{ trans('login.remember_me') }}
                            </label>
                        </div>
                        <small class="help-block text-muted">{{ trans('login.help.remember_me') }}</small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-raised btn-primary">{{ trans('login.button.login') }}</button>
                        <a class="btn btn-link" href="{{ url('/password/email') }}">{{ trans('login.forgot_your_password') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
