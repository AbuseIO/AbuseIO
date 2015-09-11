@extends('app')

@section('content')
    <h1 class="page-header">Search</h1>
    {!! Form::open(['route' => 'admin.tickets.index', 'class' => 'form-horizontal']) !!}
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('ticket_id')) has-error @endif">
            {!! Form::label('ticket_id', 'Ticket ID:') !!}
            {!! Form::number('ticket_id', null, ['class' => 'form-control']) !!}
            @if ($errors->has('ticket_id')) <p class="help-block">{{ $errors->first('ticket_id') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('ip_address')) has-error @endif">
            {!! Form::label('ip_address', 'IP Address:') !!}
            {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
            @if ($errors->has('ip_address')) <p class="help-block">{{ $errors->first('ip_address') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('customer_code')) has-error @endif">
            {!! Form::label('customer_code', 'Customer Code:') !!}
            {!! Form::text('customer_code', null, ['class' => 'form-control']) !!}
            @if ($errors->has('customer_code')) <p class="help-block">{{ $errors->first('customer_code') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('customer_name')) has-error @endif">
            {!! Form::label('customer_name', 'Customer Name:') !!}
            {!! Form::text('customer_name', null, ['class' => 'form-control']) !!}
            @if ($errors->has('customer_name')) <p class="help-block">{{ $errors->first('customer_name') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('classification')) has-error @endif">
            {!! Form::label('classification', 'Classification:') !!}
            {!! Form::select('classification', $classification_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('classification')) <p class="help-block">{{ $errors->first('classification') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('type')) has-error @endif">
            {!! Form::label('type', 'Type:') !!}
            {!! Form::select('type', $type_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('status')) has-error @endif">
            {!! Form::label('status', 'Status:') !!}
            {!! Form::select('status', $status_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('state')) has-error @endif">
            {!! Form::label('state', 'State:') !!}
            {!! Form::select('state', $state_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('state')) <p class="help-block">{{ $errors->first('state') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6">
            {!! Form::submit('Search', ['class'=>'btn btn-success']) !!}
            {!! Form::reset('Reset', ['class' => 'btn btn-default']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop
