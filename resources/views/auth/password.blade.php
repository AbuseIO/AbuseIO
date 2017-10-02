@extends('auth.app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5 col-centered top-buffer">
				<div class="panel panel-primary">
					<div class="panel-image">
						<img src="{{ asset('images/logo_445.png') }}" class="panel-image-preview" />
					</div>
					<div class="panel-body">
						@if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
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
						<h3>{{ trans('login.header.reset_password') }}</h3>
						<form role="form" method="POST" action="{{ url('/password/email') }}">
							{{ csrf_field() }}
							<div class="form-group label-floating @if ($errors->has('email')) has-error @endif">
								<label for="email" class="control-label">{{ trans('login.caption.email_address') }}</label>
								<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
								<span class="help-block">{{ trans('login.help.email')}}</span>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-raised btn-primary">{{ trans('login.btn.send_password_reset_link') }}</button>
								<a href="{{ url('/auth/login') }}" class="btn btn-link">{{ trans('misc.button.cancel') }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
