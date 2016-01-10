@extends('app')

@section('extrajs')
    <script src="{{ asset('/js/tickets.js') }}"></script>
@stop

@section('content')
    <h1 class="page-header">{{ trans('tickets.headers.detail') }}: {{ $ticket->id }} - {{ trans('tickets.evidence') }}: {{ $evidenceId }}</h1>

    <dl class="dl-horizontal">
        @foreach (['from', 'to', 'cc', 'subject'] as $header)
            <dt>{{ ucfirst($header) }} :</dt>
            <dd>{{ $evidence->getHeader($header) }}</dd>
        @endforeach

        @if (count($evidence->getAttachments()) > 0)
            <dt>Attachments :</dt>
            <dd>
                <table class="table table-condensed">
                    @foreach ($evidence->getAttachments() as $attachment)
                        <tr>
                            <td><a href='{{ Request::url() }}/attachment/{{ $attachment->getFilename() }}'>{{ $attachment->getFilename() }}</a></td>
                            <td>{{ filesize($evidenceTempDir.$attachment->getFilename()) }} bytes</td>
                            <td>{{ $attachment->getContentType() }}</td>
                        </tr>
                    @endforeach
                </table>
            </dd>
        @endif

            <dt>Message :</dt>
            <dd><pre>{{ $evidence->getMessageBody('text') }}</pre></dd>
    </dl>

@endsection