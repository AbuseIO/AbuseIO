@extends('app')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 text-right">
                {!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['admin.accounts.destroy', $account->id]]) !!}
                {!! link_to_route('admin.accounts.edit', trans('misc.button.edit'), $account->id, ['class' => 'btn btn-info']) !!}
                @if ( $account->disabled )
                    {!! link_to_route('admin.accounts.enable', trans('misc.button.enable'), $account->id, ['class' => 'btn btn-success']) !!}
                @else
                    {!! link_to_route('admin.accounts.disable', trans('misc.button.disable'), $account->id, ['class' => 'btn btn-warning']) !!}
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
            <dd>
                <p class="text-{{ $account->disabled ? 'warning' : 'success' }}">{{ $account->disabled ? trans('misc.disabled') : trans('misc.enabled') }}</p>
            </dd>

            <dt>{{ trans('accounts.api_key') }}</dt>
            <dd>
                <span id="token">{{ $account->token }}</span>
                {!! Form::button('<i class="fa fa-clipboard" aria-hidden="true"></i>', ['id' => 'btnCopyToClipboard', 'rel' => 'tooltip', 'title'=> trans('misc.copy_to_clipboard'), 'class' => 'btn btn-sm btn-info']) !!}
            </dd>
            @if ( $account->users->count() )
            <dt>{{ trans('accounts.members') }}</dt>
            <dd>
                @foreach( $account->users as $user )
                {!! link_to_route('admin.users.show', $user->first_name.' '.$user->last_name, $user->id, ['class' => 'label label-primary']) !!}
                @endforeach
            </dd>
            @endif
        </dl>
    </div>
</div>
@endsection

@section('extrajs')
    <script>
        $(document).on('click', '#btnCopyToClipboard', function() {
            try {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($('#token').text()).select();
                document.execCommand('copy');
                $temp.remove();
            }
            catch(err) {
                alert('{!! trans('misc.select_and_press_ctrl_c') !!}');
                return false;
            }
        });
    </script>
@stop
