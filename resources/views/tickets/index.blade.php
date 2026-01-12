@extends('app')

@section('extrajs')
<script>
    var searchroute = '{!! route('admin.tickets.search') !!}';
    var locale = '{{ asset("/i18n/$auth_user->locale.json") }}';
    var user_options = jQuery.parseJSON('{!! $user_options !!}');
</script>
<script src="{{ asset('/js/tickets.index.js') }}"></script>
@stop

@section('content')
<h1 class="page-header">{{ trans('misc.tickets') }}</h1>
<div class="row">
    <div class="col-md-4 offset-md-8 text-end">
        @if(!empty($has_unread_notes) && $has_unread_notes)
        <a href="#" id="unread-notes-alert" class="btn btn-warning" style="margin-right:8px;">
            <i class="fa fa-envelope"></i> Unread notes!
        </a>
        @endif
        <a href="{{ route('admin.incidents.create') }}" class="btn btn-info">{{ trans('tickets.button.new_event') }}</a>
        <a href="{{ route('admin.tickets.export', ['format' => 'csv']) }}" class="btn btn-info">{{ trans('misc.button.csv_export') }}</a>
    </div>
</div>
<table class="table table-striped table-sm top-buffer" id="tickets-table">
    <thead>
        <tr>
            <th>{{ trans('misc.ticket_id') }}</th>
            <th>{{ trans('misc.ip') }}</th>
            <th>{{ trans('misc.domain') }}</th>
            <th class="col-ip-owner">IP Owner</th>
            <th class="col-domain-owner">Domain Owner</th>
            <th>{{ trans('misc.type') }}</th>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('tickets.events') }}</th>
            <th>{{ trans('tickets.notes') }}</th>
            <th>{{ trans('misc.status') }}</th>
            <th>Last Update</th>
            <th class="text-end">{{ trans('misc.action') }}</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><input id="ip_owner_ref" class="form-control" placeholder="Search IP owner" /></td>
            <td><input id="domain_owner_ref" class="form-control" placeholder="Search Domain owner" /></td>
            <td>
                <select id="type_id" class="form-control">
                    <option value="">{{ trans('misc.all') }}</option>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select id="class_id" class="form-control">
                    <option value="">{{ trans('misc.all') }}</option>
                    @foreach($classes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </td>
            <td></td>
            <td>
                <select id="notes_filter" class="form-control">
                    <option value="">All</option>
                    <option value="UNREAD">Unread</option>
                </select>
            </td>
            <td>
                <select id="statuses" class="form-control">
                    <option value="">{{ trans('misc.all') }}</option>
                    <option value="OPEN_ESCALATED">{{ trans('tickets.open') }} + {{ trans('tickets.escalated') }}</option>
                    @foreach($statuses as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endsection
