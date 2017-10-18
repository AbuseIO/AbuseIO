@extends('auth.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-centered top-buffer">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-image">
                            <img src="{{ asset('images/logo_150.png') }}" class="panel-image-preview center-block" />
                        </div>
                    </div>
                    <div class="panel-body">
                        <h4>{!! trans('login.reset_password') !!}</h4>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <p>{!! trans('login.warning.whoops') !!}</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{ url('/password/reset') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group label-floating @if ($errors->has('email')) has-error @endif">
                                <label for="email" class="control-label">{{ trans('login.email_address') }}</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                                <span class="help-block">{{ trans('login.help.email')}}</span>
                            </div>
                            <div class="form-group label-floating @if ($errors->has('password')) has-error @endif">
                                <label for="password" class="control-label">{{ trans('login.password') }}</label>
                                <input type="password" class="form-control" name="password" id="password">
                                <span class="help-block">{{ trans('login.help.password') }}</span>
                            </div>
                            <div class="form-group label-floating @if ($errors->has('password_confirmation')) has-error @endif">
                                <label for="password_confirmation" class="control-label">{{ trans('login.password_confirmation') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                <span class="help-block">{{ trans('login.help.password_confirmation') }}</span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-raised btn-info">{{ trans('login.reset_password') }}</button>
                                <a href="{{ url('/auth/login') }}" class="btn btn-link">{{ trans('misc.button.cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
