<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body">
                {{ $message }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-1 btnCancel" data-dismiss="modal">{{ uctrans('misc.button.cancel') }}</button>
                <button type="button" class="btn {{ $confirm_class }} btnConfirm" data-dismiss="modal">{{ $confirm }}</button>
            </div>
        </div>
    </div>
</div>