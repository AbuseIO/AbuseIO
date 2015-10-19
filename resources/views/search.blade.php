@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.search') }}</h1>
    {!! Form::open(['route' => 'admin.tickets.index', 'class' => 'form-horizontal']) !!}
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('ticket_id')) has-error @endif">
            {!! Form::label('ticket_id', trans('misc.ticket_id').':') !!}
            {!! Form::number('ticket_id', null, ['class' => 'form-control']) !!}
            @if ($errors->has('ticket_id')) <p class="help-block">{{ $errors->first('ticket_id') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('ip_address')) has-error @endif">
            {!! Form::label('ip_address', trans('misc.ip_address').':') !!}
            {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
            @if ($errors->has('ip_address')) <p class="help-block">{{ $errors->first('ip_address') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('customer_code')) has-error @endif">
            {!! Form::label('customer_code', trans('search.customer_code').':') !!}
            {!! Form::text('customer_code', null, ['class' => 'form-control']) !!}
            @if ($errors->has('customer_code')) <p class="help-block">{{ $errors->first('customer_code') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('customer_name')) has-error @endif">
            {!! Form::label('customer_name', trans('search.customer_name').':') !!}
            {!! Form::text('customer_name', null, ['class' => 'form-control']) !!}
            @if ($errors->has('customer_name')) <p class="help-block">{{ $errors->first('customer_name') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('classification')) has-error @endif">
            {!! Form::label('classification', trans('misc.classification').':') !!}
            {!! Form::select('classification', $classification_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('classification')) <p class="help-block">{{ $errors->first('classification') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('type')) has-error @endif">
            {!! Form::label('type', trans('misc.type').':') !!}
            {!! Form::select('type', $type_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6 @if ($errors->has('status')) has-error @endif">
            {!! Form::label('status', trans('misc.status').':') !!}
            {!! Form::select('status', $status_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
        </div>
        <div class="col-md-6 @if ($errors->has('state')) has-error @endif">
            {!! Form::label('state', trans('misc.ticket_state').':') !!}
            {!! Form::select('state', $state_selection, null, ['class' => 'form-control']) !!}
            @if ($errors->has('state')) <p class="help-block">{{ $errors->first('state') }}</p> @endif
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-6">
            {!! Form::submit(trans('search.button.search'), ['class'=>'btn btn-success']) !!}
            {!! Form::reset(trans('misc.button.reset'), ['class' => 'btn btn-default']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop
