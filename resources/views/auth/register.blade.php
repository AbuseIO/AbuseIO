@extends('auth.app')

@section('content')
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-primary">
				<div class="panel-heading"><h4>{!! trans('login.header.register') !!}</h4></div>
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
					<div class="col-sm-12">
						<form role="form" method="POST" action="{{ url('/auth/register') }}">
							{{ csrf_field() }}
							<div class="form-group label-floating @if ($errors->has('name')) has-error @endif">
								<label for="name" class="control-label">{{ trans('misc.name') }}</label>
								<input type="text" class="form-control" name="email" id="email" value="{{ old('name') }}">
								<span class="help-block">{{ trans('misc.help.please_enter_your')}} {{ trans('misc.name') }}</span>
							</div>
							<div class="form-group label-floating @if ($errors->has('email')) has-error @endif">
								<label for="email" class="control-label">{{ trans('caption.email_address') }}</label>
								<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
								<span class="help-block">{{ trans('login.help.email') }}</span>
							</div>
							<div class="form-group label-floating @if ($errors->has('password')) has-error @endif">
								<label for="password" class="control-label">{{ trans('login.caption.password') }}</label>
								<input type="password" class="form-control" name="password" id="password">
								<span class="help-block">{{ trans('login.help.password') }}</span>
							</div>
							<div class="form-group label-floating @if ($errors->has('password_confirmation')) has-error @endif">
								<label for="password_confirmation" class="control-label">{{ trans('login.caption.password_confirmation') }}</label>
								<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
								<span class="help-block">{{ trans('login.help.password_confirmation') }}</span>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-raised btn-primary">{{ trans('caption.register') }}</button>
								<a href="{{ url('/auth/login') }}" class="btn btn-link">{{ trans('misc.button.cancel') }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
