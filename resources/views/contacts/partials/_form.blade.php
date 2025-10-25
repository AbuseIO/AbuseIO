<div class="form-group @if ($errors->has('reference')) has-error @endif">
    <label for="reference" class="col-sm-2 control-label">{{ trans('contacts.reference') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="reference" id="reference" value="{{ old('reference', isset($contact) ? $contact->reference : null) }}" class="form-control" />
        @if ($errors->has('reference')) <p class="help-block">{{ $errors->first('reference') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name" class="col-sm-2 control-label">{{ trans('misc.name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="name" id="name" value="{{ old('name', isset($contact) ? $contact->name : null) }}" class="form-control" />
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="email" class="col-sm-2 control-label">{{ trans('misc.email') }}:</label>
    <div class="col-sm-10">
        <input type="email" name="email" id="email" value="{{ old('email', isset($contact) ? $contact->email : null) }}" class="form-control" multiple="true" />
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>
@if ($auth_user->hasRole('admin') && $auth_user->account->isSystemAccount())
    <div class="form-group @if ($errors->has('account_id')) has-error @endif">
        <label for="account_id" class="col-sm-2 control-label">{{ trans_choice('misc.accounts', 1) }}:</label>
        <div class="col-sm-10">
            <select name="account_id" id="account_id" class="form-control">
                @foreach ($accounts as $id => $name)
                    <option value="{{ $id }}" @if (old('account_id', isset($selectedAccount) ? $selectedAccount : null) == $id) selected @endif>{{ $name }}</option>
                @endforeach
            </select>
            @if ($errors->has('account_id')) <p class="help-block">{{ $errors->first('account_id') }}</p> @endif
        </div>
    </div>
@endif

<div class="form-group @if ($errors->has('notificationMethods')) has-error @endif">
    <label for="auto_notify" class="col-sm-2 control-label">{{ trans('contacts.notification') }}:</label>
    <div class="col-sm-10">
        @foreach ($notificationService->listAll() as $method)
            <div class="checkbox">
                <label style="color:initial"><input type="checkbox" name="notificationMethods[]" value="{{ $method }}" @if($notificationService->hasNotificationMethod($contact, $method)) checked @endif /> {{ $method }}</label>
            </div>
        @endforeach
        @if ($errors->has('notificationMethods')) <p class="help-block"> {{ trans('contacts.no_notification_methods') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('api_host')) has-error @endif">
    <label for="api_host" class="col-sm-2 control-label">{{ trans('contacts.api_host') }}:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="url" name="api_host" id="api_host_url" value="{{ old('api_host', isset($contact) ? $contact->api_host : null) }}" class="form-control" placeholder="http://abuseio.domain.tld:1234/api/v1" />
            <span class="input-group-btn">
                <button id="checkApiURL" title="{!! trans('misc.refresh') !!}" class="btn"  type="button">
                    <i id="checkApiUrlGlyph" class="glyphicon @if (!empty($contact->api_host)) glyphicon-ok @else glyphicon-question-sign @endif"></i>
                </button>
            </span>
        </div>
        @if ($errors->has('api_host')) <p class="help-block">{{ $errors->first('api_host') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('token')) has-error @endif">
    <label for="token" class="col-sm-2 control-label">{{ trans('misc.api_key') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="token" id="token" value="{{ old('token', isset($contact) ? $contact->token : null) }}" class="form-control" />
        @if ($errors->has('token')) <p class="help-block">{{ $errors->first('token') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <label for="enabled" class="col-sm-2 control-label">{{ trans('misc.status') }}:</label>
    <div class="col-sm-10">
        <select name="enabled" id="enabled" class="form-control">
            <option value="1" @if (old('enabled', isset($contact) ? $contact->enabled : null) == 1) selected @endif>{{ trans('misc.enabled') }}</option>
            <option value="0" @if (old('enabled', isset($contact) ? $contact->enabled : null) == 0) selected @endif>{{ trans('misc.disabled') }}</option>
        </select>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
    </div>
</div>


@section('extrajs')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '#checkApiURL', function() {
            $.post('/admin/verifyexternalapi', {url: $('#api_host_url').val()}, function (data) {
                console.dir($('#api_host').val());
                console.dir(data);
            })
                .fail(function (data) {
                    alert('Error, ' + data.responseJSON.error);
                })
                .success(function (data) {
                   if($('#checkApiUrlGlyph').hasClass('glyphicon-question-sign')) {
                       $('#checkApiUrlGlyph').removeClass('glyphicon-question-sign');
                       $('#checkApiUrlGlyph').addClass('glyphicon-ok');
                   }
                });
        });
        $(document).on('keypress', '#api_host_url', function() {
            if ($('#checkApiUrlGlyph').hasClass('glyphicon-ok')) {
                $('#checkApiUrlGlyph').removeClass('glyphicon-ok');
                $('#checkApiUrlGlyph').addClass('glyphicon-question-sign');
            }
        })
    </script>
@stop