@extends('app')

@section('content')
<h1 class="page-header">{{ trans('misc.netblocks') }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        <a href="{{ route('admin.netblocks.create') }}" class="btn btn-info">{{ trans('netblocks.button.new_netblock') }}</a>
        <a href="{{ route('admin.netblocks.export', ['format' => 'csv']) }}" class="btn btn-info">{{ trans('misc.button.csv_export') }}</a>
    </div>
</div>
<table class="table table-striped" id="netblocks-table">
    <thead>
    <tr>
        <th>{{ trans('netblocks.first_ip') }}</th>
        <th>{{ trans('netblocks.last_ip') }}</th>
        <th>{{ trans('misc.contact') }}</th>
        <th class="text-right">{{ trans('misc.action') }}</th>
    </tr>
    </thead>
</table>
@endsection

@section('extrajs')
<script>
     $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#netblocks-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.netblocks.search') !!}',
            columnDefs: [{
                targets: -1,
                data: null,
                defaultContent: ''
            }],
            language: {
                url: '{{ asset("/i18n/$auth_user->locale.json") }}'
            },
            columns: [
                { data: 'first_ip', name: 'first_ip' },

                { data: 'contacts_name', name: 'contacts.name' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-right" },
            ]
        });
    });
</script>
@endsection
