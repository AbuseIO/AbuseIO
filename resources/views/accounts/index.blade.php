@extends('app')

@section('content')
<h1 class="page-header">{{ trans('misc.nav_accounts') }}</h1>
<div class="row">
    <div class="col-md-3 offset-md-9 text-end">
        @if ($auth_user->account->isSystemAccount() )
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-info">{{ trans('accounts.button.new_account') }}</a>
        @endif
    </div>
</div>
@if ( !$accounts->count() )
<div class="alert alert-info top-buffer"><i class="fa fa-info-circle"></i> {{ trans('accounts.no_accounts')}}</div>
@else
<table class="table table-striped top-buffer" id="accounts-table">
    <thead>
    <tr>
        <th>{{ trans('misc.name') }}</th>
        <th>{{ trans('misc.description') }}</th>
        <th class="text-end">{{ trans('misc.action') }}</th>
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
            ajax: '{!! route('admin.accounts.search') !!}',
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
            { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-end" },
        ]
    });
    });
</script>
@endsection
