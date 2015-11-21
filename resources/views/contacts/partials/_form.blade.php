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
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('rpc_host')) has-error @endif">
    {!! Form::label('rpc_host', trans('contacts.rpchost').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::url('rpc_host', null, ['class' => 'form-control', 'placeholder'=> 'http://rpc.domain.tld:1234/RPC']) !!}
        @if ($errors->has('rpc_host')) <p class="help-block">{{ $errors->first('rpc_host') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('rpc_key')) has-error @endif">
    {!! Form::label('rpc_key', trans('contacts.rpckey').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('rpc_key', null, ['class' => 'form-control']) !!}
        @if ($errors->has('rpc_key')) <p class="help-block">{{ $errors->first('rpc_key') }}</p> @endif
    </div>
</div>
<div class="form-group">
    {!! Form::label('auto_notify', trans('contacts.notification').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('auto_notify', [0 => trans('misc.manual'), 1 => trans('misc.automatic')], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('enabled', trans('misc.status').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('enabled', [0 => trans('misc.disabled'), 1 => trans('misc.enabled')], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
