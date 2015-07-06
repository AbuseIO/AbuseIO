<div class="form-group">
    {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('reference', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
       {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'E-Mail addresses:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('rpc_host', 'RPC Hosts:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('rpc_host', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('rpc_key', 'RPC Key:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('rpc_key', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('auto_notify', 'Notifications:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('auto_notify', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
    </div>
</div>
