@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.migrate.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.migrate.title') }}
@endsection

@section('container')

    <p style="font-size: 72px; text-align: center"><i class="fa fa-gear fa-spin"></i></p>
	<pre><code></code></pre>

    <div class="buttons">
        <a href="{{ route('LaravelInstaller::final') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>

@endsection

@section('scripts')
    <script>
        console.log($('code'));
    </script>

@endsection

