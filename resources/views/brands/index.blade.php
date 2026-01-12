@extends('app')

@section('content')
<h1 class="page-header">{{ trans('misc.nav_brands') }}</h1>
<div class="row">
    <div class="col-md-3 offset-md-9 text-end">
        <a href="{{ route('admin.brands.create') }}" class="btn btn-info">{{ trans('brands.button.new_brand') }}</a>
    </div>
</div>
@if ( !$brands->count() )
<div class="alert alert-info top-buffer"><i class="fa fa-info-circle"></i> {{ trans('brands.no_brands')}}</div>
@else
<table class="table table-striped top-buffer" id="brands-table">
    <thead>
    <tr>
        <th>{{ trans('misc.name') }}</th>
        <th>{{ trans('misc.company_name') }}</th>
        <th>{{ trans('misc.text') }}</th>
        <th>{{ trans('brands.logo') }}</th>
        <th>{{ trans('misc.status') }}</th>
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

        $('#brands-table').DataTable({
            processing: true,
            serverSide: true,
            sort: false,
            ajax: '{!! route('admin.brands.search') !!}',
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
            { data: 'company_name', name: 'company_name' },
            { data: 'introduction_text', name: 'introduction_text' },
            { data: 'logo', name: 'logo' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, class: "text-end" },
        ]
    });
    });
</script>
@endsection
