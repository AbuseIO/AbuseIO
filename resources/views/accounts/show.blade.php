@extends('app')

@section('content')
<h1 class="page-header">{{ trans('accounts.header.detail') }}: {{ $account->name }}</h1>
<div class="row">
    <div class="col-sm-12 text-right">
        {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.accounts.destroy', $account->id]]) !!}
        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
        @if ( $account->disabled )
            <a href="{{ route('admin.accounts.enable', $account->id) }}" class="btn btn-success">{{ trans('misc.button.enable') }}</a>
        @else
            <a href="{{ route('admin.accounts.disable', $account->id) }}" class="btn btn-warning">{{ trans('misc.button.disable') }}</a>
        @endif
        {!! Form::submit(trans('misc.button.delete'), ['class' => 'btn btn-danger'.(($account->isSystemAccount()) ? ' disabled' : '')]) !!}
        {!! Form::close() !!}
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.id') }}</dt>
    <dd>{{ $account->id }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $account->name }}</dd>

    <dt>{{ trans('misc.description') }}</dt>
    <dd>{{ $account->description }}</dd>

    <dt>{{ trans_choice('misc.brands', 1) }}</dt>
    <dd>{{ $brand->name }}</dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $account->disabled ? trans('misc.disabled') : trans('misc.enabled') }}</dd>

    <dt>{{ trans('accounts.api_key') }}</dt>
    <dd>
        {!! Form::input('text', 'token', $account->token, ['id' => 'token', 'style' => 'padding:0; margin-right:10px; width:300px; border:none;']) !!}
        {!! Form::button('<i class="fa fa-clipboard" aria-hidden="true"></i>', ['id' => 'btnCopyToClipboard', 'rel' => 'tooltip', 'title'=> trans('misc.copy_to_clipboard'), 'class' => 'btn btn-sm btn-info']) !!}
    </dd>
</dl>

@if ( $account->users->count() )
<h3 class="page-header">{{ trans('accounts.linked_users') }}: {{ $account->users->count() }}</h3>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('misc.id') }}</th>
            <th>{{ trans('misc.name') }}</th>
            <th class="text-right">{{ trans('misc.action') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach( $account->users as $user )
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
        <td class="text-right">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-xs">{{ trans('misc.button.details') }}</a>
        </td>
    </tr>
    @endforeach
    </tbody>
    </table>
@endif

@endsection

@section('extrajs')
    <script>
        $(document).on('click', '#btnCopyToClipboard', function() {
            var inp = document.getElementById('token');
            if (inp && inp.select) {
                // select text
                inp.select();

                try {
                    // copy text
                    document.execCommand('copy');
                    inp.blur();
                }
                catch (err) {
                    alert('{!! trans('please_press_ctrl_cmd_to_copy') !!}');
                    return false;
                }
                var tooltip = $('[rel="tooltip"]');
                tooltip.tooltip('show');

                setTimeout( function() {
                    tooltip.tooltip('destroy');
                }, 3000);
            }
        });
    </script>
@stop
