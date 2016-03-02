@extends('app')

@section('content')
<h1 class="page-header">{{ trans('misc.domains') }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        {!! link_to_route('admin.domains.create', trans('domains.button.new_domain'), [ ], ['class' => 'btn btn-info']) !!}
        {!! link_to_route('admin.domains.export', trans('misc.button.csv_export'), ['format' => 'csv'], ['class' => 'btn btn-info']) !!}
    </div>
</div>

<table class="table table-striped" id="domains-table">
    <thead>
    <tr>
        <th>{{ trans('domains.domainname') }}</th>
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

        $('#domains-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.domains.search') .'/query/' !!}',
            columnDefs: [{
                targets: -1,
                data: null,
                defaultContent: ''
            }],
            language: {
                url: '{{ asset("/i18n/$auth_user->locale.json") }}'
            },
            columns: [
                { data: 'name', name: 'domains.name' },
                { data: 'contacts_name', name: 'contacts.name' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-right" },
            ]
        });
    });
</script>
@endsection
