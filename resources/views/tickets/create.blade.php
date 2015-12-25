@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.new') }}</h1>

    {!! Form::open(['method' => 'post', 'files' => true]) !!}

    <div class="form-group @if ($errors->has('source')) has-error @endif">
        {!! Form::label('source', trans('tickets.source').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('source', null, ['class' => 'form-control']) !!}
            @if ($errors->has('source')) <p class="help-block">{{ $errors->first('source') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('address')) has-error @endif">
        {!! Form::label('address', trans('misc.ip_address').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('address', null, ['class' => 'form-control']) !!}
            @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('domain')) has-error @endif">
        {!! Form::label('domain', trans('misc.domain').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('domain', null, ['class' => 'form-control']) !!}
            @if ($errors->has('domain')) <p class="help-block">{{ $errors->first('domain') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('uri')) has-error @endif">
        {!! Form::label('uri', trans('misc.uri').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('uri', null, ['class' => 'form-control']) !!}
            @if ($errors->has('uri')) <p class="help-block">{{ $errors->first('uri') }}</p> @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('class', trans('misc.classification').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('class', [ '0' => 'todo class' ], null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('type', trans('misc.type').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('type', [ '0' => 'todo type' ], null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('timestamp')) has-error @endif">
        {!! Form::label('timestamp', trans('tickets.timestamp').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('timestamp', null, ['class' => 'form-control']) !!}
            @if ($errors->has('timestamp')) <p class="help-block">{{ $errors->first('timestamp') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('information')) has-error @endif">
        {!! Form::label('information', trans('tickets.information').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('information', null, ['class' => 'form-control']) !!}
            @if ($errors->has('information')) <p class="help-block">{{ $errors->first('information') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('evidenceFile')) has-error @endif">
        {!! Form::label('evidenceFile', trans('tickets.evidence').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('evidenceFile', ['class' => 'form-control']) !!}
            @if ($errors->has('evidenceFile')) <p class="help-block">{{ $errors->first('evidenceFile') }}</p> @endif
        </div>
    </div>

    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
@stop
