@extends('app')

@section('extrajs')
    <script type="text/javascript">
        $(document).ready(function(){
            var displayInput = $('input[name=timestamp_display]');
            var hiddenInput = $('input[name=timestamp]');
            var pad = function(n){ return n < 10 ? '0' + n : n; };
            function toIsoLocal(d){
                return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
            }
            function ddmmToIso(str){
                var m = str && str.match(/^(\d{2})-(\d{2})-(\d{4})\s+(\d{2}):(\d{2})$/);
                if (!m) return null;
                return m[3] + '-' + m[2] + '-' + m[1] + 'T' + m[4] + ':' + m[5];
            }
            function isoToUnix(str){
                // Try native Date parsing first (handles YYYY-MM-DDTHH:MM[:SS])
                var d = new Date(str);
                if (!isNaN(d.getTime())) {
                    return Math.floor(d.getTime() / 1000);
                }
                // Fallback: parse YYYY-MM-DDTHH:MM or with seconds via regex
                var m = str && str.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(?::(\d{2}))?$/);
                if (!m) return null;
                d = new Date(parseInt(m[1],10), parseInt(m[2],10)-1, parseInt(m[3],10), parseInt(m[4],10), parseInt(m[5],10), parseInt(m[6]||'0',10));
                return Math.floor(d.getTime() / 1000);
            }

            if({{ json_encode(old('timestamp') !== null) }}){
                var tsOld = {{ json_encode(old('timestamp')) }};
                if (tsOld && /^\d+$/.test(tsOld)) {
                    var d = new Date(parseInt(tsOld, 10) * 1000);
                    displayInput.val(toIsoLocal(d));
                    hiddenInput.val(String(tsOld));
                } else if (tsOld) {
                    var iso = ddmmToIso(tsOld);
                    if (iso) {
                        displayInput.val(iso);
                        var u = isoToUnix(iso);
                        if (u) hiddenInput.val(String(u));
                    } else if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(tsOld)) {
                        displayInput.val(tsOld);
                        var u2 = isoToUnix(tsOld);
                        if (u2) hiddenInput.val(String(u2));
                    }
                }
            }

            $('#incident-form').on('submit', function(){
                var val = displayInput.val();
                var unix = isoToUnix(val);
                if (unix) {
                    hiddenInput.val(String(unix));
                }
            });
        });
    </script>
@endsection

@section('content')
<h1 class="page-header">{{ trans('tickets.header.new') }}</h1>

@if (session('message'))
    <div class="alert alert-danger">
        {{ session('message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>{{ __('Validation failed') }}:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="incident-form" method="POST" action="{{ route('admin.incidents.store') }}" enctype="multipart/form-data" class="form-horizontal">
    @csrf

    <div class="form-group @if ($errors->has('source')) has-error @endif">
        <label for="source" class="col-sm-2 control-label">{{ trans('tickets.source') }}:</label>
        <div class="col-sm-10">
            <input type="text" name="source" id="source" value="{{ old('source') }}" class="form-control">
            @if ($errors->has('source')) <p class="help-block">{{ $errors->first('source') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('ip')) has-error @endif">
        <label for="ip" class="col-sm-2 control-label">{{ trans('misc.ip_address') }}:</label>
        <div class="col-sm-10">
            <input type="text" name="ip" id="ip" value="{{ old('ip') }}" class="form-control">
            @if ($errors->has('ip')) <p class="help-block">{{ $errors->first('ip') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('domain')) has-error @endif">
        <label for="domain" class="col-sm-2 control-label">{{ trans('misc.domain') }} ({{ trans('misc.optional') }}):</label>
        <div class="col-sm-10">
            <input type="text" name="domain" id="domain" value="{{ old('domain') }}" class="form-control">
            @if ($errors->has('domain')) <p class="help-block">{{ $errors->first('domain') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('class')) has-error @endif">
        <label for="class" class="col-sm-2 control-label">{{ trans('misc.classification') }}:</label>
        <div class="col-sm-10">
            <select name="class" id="class" class="form-control">
                <option value="">{{ trans('misc.select_one') }}</option>
                @foreach($classes as $key => $label)
                    <option value="{{ $key }}" @if(old('class') == $key) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
            @if ($errors->has('class')) <p class="help-block">{{ $errors->first('class') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('type')) has-error @endif">
        <label for="type" class="col-sm-2 control-label">{{ trans('misc.type') }}:</label>
        <div class="col-sm-10">
            <select name="type" id="type" class="form-control">
                <option value="">{{ trans('misc.select_one') }}</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" @if(old('type') == $key) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
            @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('timestamp')) has-error @endif">
        <label for="timestamp" class="col-sm-2 control-label">{{ trans('tickets.timestamp') }}:</label>
        <div class="col-sm-10">
            <input type="datetime-local" name="timestamp_display" id="timestamp_display" value="{{ old('timestamp') }}" class="form-control">
            <input type="hidden" name="timestamp" id="timestamp" value="{{ old('timestamp') }}">
            @if ($errors->has('timestamp')) <p class="help-block">{{ $errors->first('timestamp') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('information')) has-error @endif">
        <label for="information" class="col-sm-2 control-label">{{ trans('tickets.information') }}</label>
        <div class="col-sm-10">
            <textarea name="information" id="information" rows="5" placeholder="YAML formatted dataset (field: value<NEWLINE>)" class="form-control">{{ old('information') }}</textarea>
            @if ($errors->has('information')) <p class="help-block">{{ $errors->first('information') }}</p> @endif
        </div>
    </div>

    <div class="form-group @if ($errors->has('evidenceFile')) has-error @endif">
        <label for="evidenceFile" class="col-sm-2 control-label">{{ trans('tickets.evidence') }}:</label>
        <div class="col-sm-10">
            <input type="file" name="evidenceFile" id="evidenceFile">
            @if ($errors->has('evidenceData')) <p class="help-block">{{ $errors->first('evidenceData') }}</p> @endif
        </div>
    </div>

    <button type="submit" class="btn btn-success">Submit</button>
</form>
@endsection
