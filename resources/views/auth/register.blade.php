@extends('auth.app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5 col-centered top-buffer">
				<div class="card">
					<div class="card-header bg-primary">
						<div class="text-center">
							<img src="{{ asset('images/logo_150.png') }}" alt="{{ Config::get('app.name') }}"/>
						</div>
					</div>
					<div class="card-body">
						<h4 class="card-title">{{ trans('login.register') }}</h4>
						@if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
						<form role="form" method="POST" action="{{ url('/auth/register') }}">
							{{ csrf_field() }}
							<div class="form-group label-floating @if ($errors->has('name')) has-error @endif">
								<label for="name" class="control-label">{{ trans('misc.name') }}</label>
								<input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
								@if ($errors->has('name'))<small class="help-block text-danger">{{ $errors->first('name') }}</small>@endif
							</div>
							<div class="form-group label-floating @if ($errors->has('email')) has-error @endif">
								<label for="email" class="control-label">{{ trans('caption.email_address') }}</label>
								<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
								@if ($errors->has('email'))<small class="help-block text-danger">{{ $errors->first('email') }}</small>@endif
							</div>
							<div class="form-group label-floating @if ($errors->has('password')) has-error @endif">
								<label for="password" class="control-label">{{ trans('login.caption.password') }}</label>
								<input type="password" class="form-control" name="password" id="password">
								@if ($errors->has('password'))<small class="help-block text-danger">{{ $errors->first('password') }}</small>@endif
							</div>
							<div class="form-group label-floating @if ($errors->has('password_confirmation')) has-error @endif">
								<label for="password_confirmation" class="control-label">{{ trans('login.password_confirmation') }}</label>
								<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
								@if ($errors->has('password_confirmation'))<small class="help-block text-danger">{{ $errors->first('password_confirmation') }}</small>@endif
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-raised btn-info">{{ trans('caption.register') }}</button>
								<a href="{{ url('/auth/login') }}" class="btn btn-link">{{ trans('misc.button.cancel') }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
