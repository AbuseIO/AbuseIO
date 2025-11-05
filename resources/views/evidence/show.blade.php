@extends('app')

@section('content')
<h1 class="page-header">{{ trans('evidence.header.detail') }}{{ $evidence->id }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        <a href="{{ route('admin.evidence.download', $evidence->id) }}" class="btn btn-info">{{ trans('evidence.button.download') }}</a>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.back') }}</a>
    </div>
</div>
@if(is_object($evidence))
@php($data = $evidence->data)
<dl class="dl-horizontal">
    @foreach (['from', 'subject'] as $header)
        @php($headerValue = ($data && isset($data['headers'][$header])) ? $data['headers'][$header] : ($header === 'from' ? $evidence->sender : $evidence->subject))
        @if(!empty($headerValue))
            <dt>{{ trans("evidence.{$header}") }} :</dt>
            <dd>{{ $headerValue }}</dd>
        @endif
    @endforeach
    @if ($data && isset($data['files']) && count($data['files']) > 0)
        <dt>{{ trans('evidence.attachment') }} :</dt>
        <dd>
            <table class="table table-condensed">
            @foreach ($data['files'] as $index => $attachment)
                <tr>
                    <td>
                        <a href="{{ route('admin.evidence.attachment', [$evidence->id, $attachment->getFilename()]) }}">{{ $attachment->getFilename() }}</a>
                        @if ($data && isset($data['files_dir']))
                        <span class="badge">{{ hFileSize(Storage::disk('local_temp')->size("{$data['files_dir']}/{$attachment->getFilename()}")) }}</span>
                        @endif
                        <span class="label label-primary">{{ $attachment->getContentType() }}</span>
                    </td>
                </tr>
            @endforeach
            </table>
        </dd>
    @endif
    <dt>{{ trans('evidence.message') }} :</dt>
    @if ($data && isset($data['message']))
        <dd><pre>{{ (is_object($data['message'])) ? print_r($data['message'], true) : $data['message'] }}</pre></dd>
    @else
        <dd><em>{{ trans('misc.notavailable') }}</em></dd>
    @endif
</dl>
@endif
@endsection
