<div class="modal fade" id="user" tabindex="-1" role="dialog" aria-labelledby="userLabel" aria-hidden="true">
    <form name="userForm" id="userForm" action="null" method="null">
        <input id="id" name="id" type="hidden" value="null">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('first_name', trans('users.first_name'), ['class' => 'control-label']) !!}
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'data-lpignore' => 'true']) !!}
                                <div class="invalid-feedback">First name required.</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('last_name', trans('users.last_name'), ['class' => 'control-label']) !!}
                                {!! Form::text('last_name', null, ['class' => 'form-control', 'required', 'data-lpignore' => 'true']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', trans('misc.email'), ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'required', 'data-lpignore' => 'true']) !!}
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('password', trans('profile.password'), ['class' => 'control-label']) !!}
                                {!! Form::password('password', ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group label-floating">
                                {!! Form::label('password_confirmation', trans('profile.password_confirmation'), ['class' => 'control-label']) !!}
                                {!! Form::password('password_confirmation', ['class' => 'form-control', 'data-lpignore' => 'true']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" id="dropdown-menu">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('locale', ucfirst(trans_choice('misc.language', 1))) !!}
                                {!! Form::select('locale', $locale_selection, $locale_selected, ['class' => 'form-control', 'required', 'data-lpignore' => 'true']) !!}
                            </div>
                        </div>
                        @if ($auth_user->account->isSystemAccount())
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ $account_selected }}
                                    {!! Form::label('account_id', ucfirst(trans_choice('misc.account', 1))) !!}
                                    {!! Form::select('account_id', $account_selection, $account_selected, ['class' => 'form-control', 'required', 'data-lpignore' => 'true']) !!}
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('account_id', $auth_user->account->id) !!}
                        @endif
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('roles', trans_choice('misc.role', 2)) !!}
                                {!! Form::select('roles[]', $roles_selection, $roles_selected, ['class' => 'form-control', 'required', 'data-lpignore' => 'true', 'id' => 'roles', 'multiple']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('disabled', "true", $disabled_checked, ['id' => 'disabled', 'data-lpignore' => 'true']) !!} {{ trans('misc.disabled') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">{{ uctrans('misc.button.cancel') }}</button>
                    <button type="button" class="btn btn-success">{{ uctrans('misc.button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>