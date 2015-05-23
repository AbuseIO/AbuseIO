<div class="form-group">
    {!! Form::label('reference', 'Reference:') !!}
    {!! Form::text('reference') !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name') !!}
</div>
<div class="form-group">
    {!! Form::label('email', 'E-Mail addresses:') !!}
    {!! Form::text('email') !!}
</div>
<div class="form-group">
    {!! Form::label('rpc_host', 'RPC Hosts:') !!}
    {!! Form::text('rpc_host') !!}
</div>
<div class="form-group">
    {!! Form::label('rpc_key', 'RPC Key:') !!}
    {!! Form::text('rpc_key') !!}
</div>
<div class="form-group">
    {!! Form::label('auto_notify', 'Notifications:') !!}
    {!! Form::text('auto_notify') !!}
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}
</div>