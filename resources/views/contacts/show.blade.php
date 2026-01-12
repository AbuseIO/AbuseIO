@extends('app')

@section('content')
<h1 class="page-header">{{ isset($contact) ? $contact->name : '' }}</h1>
<div class="row">
    <div class="offset-sm-9 col-sm-3 text-end">
        <form name="delContact" class="form-inline" method="POST" action="{{ url('admin/contacts/' . $contact->id) }}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <a href="{{ url('admin/contacts/' . $contact->id . '/edit') }}" class="btn btn-info">{{ trans('misc.button.edit') }}</a>
            <button type="button" name="anonBtn" class="btn btn-warning">{{ trans('misc.button.anonymize') }}</button>
            <button type="button" name="delBtn" class="btn btn-danger">{{ trans('misc.button.delete') }}</button>
        </form>
    </div>
</div>
<dl class="dl-horizontal">
    <dt>{{ trans('misc.database_id') }}</dt>
    <dd>{{ $contact->id }}</dd>

    <dt>{{ trans_choice('misc.accounts', 1) }}</dt>
    <dd>{{ ($contact && $contact->account) ? $contact->account->name : '' }}</dd>

    <dt>{{ trans('contacts.reference') }}</dt>
    <dd>{{ $contact->reference }}</dd>

    <dt>{{ trans('misc.name') }}</dt>
    <dd>{{ $contact->name }}</dd>

    <dt>{{ trans('misc.email') }}</dt>
    <dd>{{ $contact->email ?: trans('misc.notavailable') }}</dd>

    <dt>{{ trans('contacts.api_host') }}</dt>
    <dd>{{ $contact->api_host ?: trans('misc.notavailable') }}</dd>

    <dt>{{ trans('contacts.notification') }}</dt>
    <dd>
        @forelse ($contact->notificationMethods as $method)
            {!! $method->method !!} <br />
        @empty
            {{ trans('contacts.no_notification_methods') }}
        @endforelse
    </dd>

    <dt>{{ trans('misc.status') }}</dt>
    <dd>{{ $contact->enabled ? trans('misc.enabled') : trans('misc.disabled') }}</dd>
</dl>

@if ( $contact->netblocks->count() )
<div class="card border-info">
    <div class="card-header bg-info text-white"><h3 class="card-title">{{ trans('contacts.linked_netblocks') }}</h3></div>
    <table class="table table-striped info">
        <thead>
            <tr>
                <th class="col-sm-1">{{ trans('misc.database_id') }}</th>
                <th class="col-sm-4">{{ trans('netblocks.first_ip') }}</th>
                <th>{{ trans('netblocks.last_ip') }}</th>
            </tr>
        </thead>
        @foreach( $contact->netblocks as $netblock )
        <tr>
            <td>{{ $netblock->id }}</td>
            <td>{{ $netblock->first_ip }}</td>
            <td>{{ $netblock->last_ip }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

@if ( $contact->domains->count() )
<div class="card border-info">
    <div class="card-header bg-info text-white"><h3 class="card-title">{{ trans('contacts.linked_domains') }}</h3></div>
    <table class="table table-striped info">
        <thead>
            <tr>
                <th class="col-sm-1">{{ trans('misc.database_id') }}</th>
                <th>{{trans('domains.domainname') }}</th>
            </tr>
        </thead>
        @foreach( $contact->domains as $domain )
        <tr>
            <td>{{ $domain->id }}</td>
            <td>{{ $domain->name }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

<form name="anonContact" class="form-inline" method="POST" action="{{ route('admin.gdpr.anonymize', ['contacts' => $contact->id]) }}">
    {{ csrf_field() }}
    <input type="hidden" name="anonymize" value="1">
</form>

<!-- Confirm Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirmLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="confirmLabel">Please confirm</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to continue with this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmed">Yes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extrajs')
<script type="application/javascript">
    $('button[name="delBtn"]').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();
        var modalEl = document.getElementById('confirm');
        var modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
        $(modalEl).one('click', '#confirmed', function() {
            $form.trigger('submit');
        });
        modal.show();
    });

    $('button[name="anonBtn"]').on('click', function(e) {
        var $form = $('form[name="anonContact"]');
        e.preventDefault();
        var modalEl = document.getElementById('confirm');
        var modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
        $(modalEl).one('click', '#confirmed', function() {
            $form.trigger('submit');
        });
        modal.show();
    });
</script>
@endsection
