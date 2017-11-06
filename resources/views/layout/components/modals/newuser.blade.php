<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('misc.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body">
                {{ trans('misc.sentence.confirm_delete') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">{{ uctrans('misc.no') }}</button>
                <button type="button" class="btn btn-raised btn-danger" data-dismiss="modal">{{ uctrans('misc.yes') }}</button>
            </div>
        </div>
    </div>
</div>