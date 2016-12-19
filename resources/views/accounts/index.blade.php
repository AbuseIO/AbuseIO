@extends('app')

@section('content')
<h1 class="page-header">{{ trans('misc.nav_accounts') }}</h1>
<div class="row">
    <div class="col-md-3 col-md-offset-9 text-right">
        @if ($auth_user->account->isSystemAccount() )
            {!! link_to_route('admin.accounts.create', trans('accounts.button.new_account'), [ ], ['class' => 'btn btn-info']) !!}
        @endif
    </div>
</div>
@if ( !$accounts->count() )
<div class="alert alert-info top-buffer"><span class="glyphicon glyphicon-info-sign"></span> {{ trans('accounts.no_accounts')}}</div>
@else
<table class="table table-striped top-buffer" id="accounts-table">
    <thead>
    <tr>
        <th>{{ trans('misc.name') }}</th>
        <th>{{ trans('misc.description') }}</th>
        <th class="text-right">{{ trans('misc.action') }}</th>
    </tr>
    </thead>
</table>
@endif
@endsection

@section('extrajs')
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#accounts-table').DataTable({
            processing: true,
            serverSide: true,
            sort: false,
            ajax: '{!! route('admin.accounts.search') .'/query/' !!}',
            columnDefs: [ {
            targets: -1,
            data: null,
            defaultContent: " "
        } ],
        language: {
            url: '{{ asset("/i18n/$auth_user->locale.json") }}'
        },
            columns: [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-right" },
        ]
    });
    });
</script>
@endsection
