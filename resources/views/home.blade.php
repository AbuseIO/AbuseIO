@extends('app')

@section('content')
        <h1 class="page-header">Home</h1>
        <div class="jumbotron">
            <p>{{ trans('misc.abuseio_intro1') }}</p>
            <p>{{ trans('misc.abuseio_intro2') }}</p>
        </div>
        <div class="float-end">
            <p id="status">

            </p>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalTitle"></h4>
                    </div>
                    <div class="modal-body" id="modalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('extrajs')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $.get('version/', function(data) {
                $('#status').text(data.version+' '+  data.body)
                if (data.action === 'showModal') {
                    $('#modalBody').text(data.body);
                    $('#modalTitle').text(data.title);
                    (new bootstrap.Modal(document.getElementById('myModal'))).show();

                }
            }).fail(function(data) {
                ('#status').text('offline, or with errors');
            });


        });
    </script>
@stop
