@extends('auth.app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5 col-centered top-buffer">
				<div class="panel panel-default">
					<div class="panel-image">
						<img src="{{ asset('images/logo_445.png') }}" class="panel-image-preview" />
					</div>
					<div class="panel-body">
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
						<h3>Log in</h3>
						<form method="POST" action="{{ url('/auth/login') }}">
							{{ csrf_field() }}
							<div class="form-group label-floating @if ($errors->has('email')) has-error @endif">
								<label for="email" class="control-label">{{ trans('login.caption.email_address') }}</label>
								<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
								<span class="help-block">{{ trans('login.help.email')}}</span>
							</div>
							<div class="form-group label-floating @if ($errors->has('password')) has-error @endif">
								<label for="password" class="control-label">{{ trans('login.caption.password') }}</label>
								<input type="password" class="form-control" name="password" id="password">
								<span class="help-block">{{ trans('login.help.password') }}</span>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> {{ trans('login.caption.remember_me') }}
									</label>
								</div>
								<p class="help-block">{{ trans('login.help.remember_me') }}</p>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-raised btn-primary">{{ trans('login.btn.login') }}</button>
								<a class="btn btn-link" href="{{ url('/password/email') }}">{{ trans('login.link.forgot_your_password') }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
