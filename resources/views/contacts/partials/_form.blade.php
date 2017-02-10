<div class="form-group @if ($errors->has('reference')) has-error @endif">
    {!! Form::label('reference', trans('contacts.reference').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('reference', null, ['class' => 'form-control']) !!}
        @if ($errors->has('reference')) <p class="help-block">{{ $errors->first('reference') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('misc.name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('misc.email').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'multiple' => 'true']) !!}
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('notificationMethods')) has-error @endif">
    {!! Form::label('auto_notify', trans('contacts.notification').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        @foreach ($notificationService->listAll() as $method)
            <div class="checkbox">
                <label style="color:initial">{!! Form::checkbox('notificationMethods[]', $method, $notificationService->hasNotificationMethod($contact, $method)) !!} {{ $method }}</label>
            </div>
        @endforeach
        @if ($errors->has('notificationMethods')) <p class="help-block"> {{ trans('contacts.no_notification_methods') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('api_host')) has-error @endif">
    {!! Form::label('api_host', trans('contacts.api_host').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        <div class="input-group">
            {!! Form::url('api_host', null, ['class' => 'form-control', 'placeholder'=> 'http://api.domain.tld:1234/RPC']) !!}
            <span class="input-group-btn">
                <button id="checkApiURL" title="{!! trans('misc.refresh') !!}" class="btn"  type="button"><i class="glyphicon glyphicon-refresh"></i></button>
            </span>
        </div>
        @if ($errors->has('api_host')) <p class="help-block">{{ $errors->first('api_host') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('token')) has-error @endif">
    {!! Form::label('token', trans('misc.api_key').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('token', null, ['class' => 'form-control']) !!}
        @if ($errors->has('token')) <p class="help-block">{{ $errors->first('token') }}</p> @endif
    </div>
</div>
<div class="form-group">
    {!! Form::label('enabled', trans('misc.status').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('enabled', [1 => trans('misc.enabled'), 0 => trans('misc.disabled')], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
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
            $.post('/admin/verifyexternalapi', {url: $('#api_host').val()}, function (data) {
                console.dir(data);
            })
                .fail(function (data) {
                    alert('Error, ' + data.responseJSON.error);
                });
        });
    </script>
@stop