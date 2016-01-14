@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.new') }}</h1>

    {!! Form::open(['route' => 'admin.tickets.store', 'files' => true, 'class' => 'form-horizontal']) !!}

    <div class="form-group @if ($errors->has('source')) has-error @endif">
        {!! Form::label('source', trans('tickets.source').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('source', null, ['class' => 'form-control']) !!}
            @if ($errors->has('source')) <p class="help-block">{{ $errors->first('source') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('ip')) has-error @endif">
        {!! Form::label('ip', trans('misc.ip_address').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('ip', null, ['class' => 'form-control']) !!}
            @if ($errors->has('ip')) <p class="help-block">{{ $errors->first('ip') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('domain')) has-error @endif">
        {!! Form::label('domain', trans('misc.domain') . ' (' . trans('misc.optional') .'):', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('domain', null, ['class' => 'form-control']) !!}
            @if ($errors->has('domain')) <p class="help-block">{{ $errors->first('domain') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('uri')) has-error @endif">
        {!! Form::label('uri', trans('misc.uri') . ' (' . trans('misc.optional') .'):', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('uri', null, ['class' => 'form-control']) !!}
            @if ($errors->has('uri')) <p class="help-block">{{ $errors->first('uri') }}</p> @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('class', trans('misc.classification').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('class', $classes, null, ['class' => 'form-control']) !!}
            @if ($errors->has('class')) <p class="help-block">{{ $errors->first('class') }}</p> @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('type', trans('misc.type').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('type', $types, null, ['class' => 'form-control']) !!}
            @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
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
        {!! Form::label('information', trans('tickets.information'), ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('information', null, ['size' => '30x5', 'placeholder' => 'YAML formatted dataset (field: value<NEWLINE>)', 'class' => 'form-control']) !!}
            @if ($errors->has('information')) <p class="help-block">{{ $errors->first('information') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('evidenceFile')) has-error @endif">
        {!! Form::label('evidenceFile', trans('tickets.evidence').':', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::file('evidenceFile', []) !!}
            @if ($errors->has('evidenceData')) <p class="help-block">{{ $errors->first('evidenceData') }}</p> @endif
        </div>
    </div>

    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}

@stop
