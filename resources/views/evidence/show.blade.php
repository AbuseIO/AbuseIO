@extends('app')

@section('content')
<h1 class="page-header">{{ trans('evidence.header.detail') }}{{ $evidence->id }}</h1>
<div class="row">
    <div  class="col-md-3 col-md-offset-9 text-right">
        {!! link_to_route('admin.evidence.download', trans('evidence.button.download'), $evidence->id, ['class' => 'btn btn-info']) !!}
        {!! link_to(URL::previous(), trans('misc.button.back'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
@if(is_object($evidence))
<dl class="dl-horizontal">
    @foreach (['from', 'subject'] as $header)
        @if(!empty($evidence->data['headers'][$header]))
            <dt>{{ trans("evidence.{$header}") }} :</dt>
            <dd>{{ $evidence->data['headers'][$header] }}</dd>
        @endif
    @endforeach
    @if (count($evidence->data['files']) > 0)
        <dt>{{ trans('evidence.attachment') }} :</dt>
        <dd>
            <table class="table table-condensed">
            @foreach ($evidence->data['files'] as $index => $attachment)
                <tr>
                    <td>
                        {!! link_to_route('admin.evidence.attachment', $attachment->getFilename(), [$evidence->id, $attachment->getFilename()]) !!}
                        <span class="badge">{{ hFileSize(Storage::disk('local_temp')->size("{$evidence->data['files_dir']}/{$attachment->getFilename()}")) }}</span>
                        <span class="label label-primary">{{ $attachment->getContentType() }}</span>
                    </td>
                </tr>
            @endforeach
            </table>
        </dd>
    @endif
    <dt>{{ trans('evidence.message') }} :</dt>
    <dd><pre>{{ (is_object($evidence->data['message'])) ? print_r($evidence->data['message'], true) : $evidence->data['message'] }}</pre></dd>
</dl>
@endif
@endsection
