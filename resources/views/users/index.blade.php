@extends('app')

@section('content')
<h1 class="page-header">{{ trans_choice('misc.users', 2) }}</h1>
<div class="row">
    <div class="col-md-3 offset-md-9 text-end">
        <a href="{{ route('admin.users.create') }}" class="btn btn-info">{{ trans('users.button.new_user') }}</a>
    </div>
</div>
@if ( !$users->count() )
<div class="alert alert-info top-buffer"><i class="fa fa-info-circle"></i> {{ trans('users.no_users')}}</div>
@else
<table class="table table-striped top-buffer" id="users-table">
    <thead>
        <tr>
            <th>{{ trans('misc.id') }}</th>
            <th>{{ trans('users.first_name') }}</th>
            <th>{{ trans('users.last_name') }}</th>
            <th>{{ trans_choice('misc.accounts', 1) }}</th>
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

        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.users.search') !!}',
            columnDefs: [{
                targets: -1,
                data: null,
                defaultContent: ''
            }],
            language: {
                url: '{{ asset("/i18n/$auth_user->locale.json") }}'
            },
            columns: [
                { data: 'id', name: 'users.id' },
                { data: 'first_name', name: 'users.first_name' },
                { data: 'last_name', name: 'users.last_name' },
                { data: 'account_name', name: 'accounts.name' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-end" },
            ]
        });
    });
</script>
@endsection
