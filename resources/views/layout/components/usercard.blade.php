<card class="col-sm-6 col-md-4 col-lg-3 mb-4 fade show" id="card_{{ $user->id }}">
    <div class="card">
        <div class="card-header text-white
            @if ($user->disabled)
                bg-secondary
            @else
                bg-blue
            @endif">
            <div class="row justify-content-between">
                <div class="col-10">
                    <h4 class="card-title"><span id="first_name_{{ $user->id }}">{{ $user->first_name }}</span> <span id="last_name_{{ $user->id }}">{{ $user->last_name }}</span></h4>
                </div>
                <div class="col-2 text-right">
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary bmd-btn-icon dropdown-toggle" id="userOptionsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userOptionsMenu">
                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#user" data-action="edit" data-form-method="patch" data-form-action="{{ route('admin.users.update', $user->id) }}" data-title="{{ uctrans('users.header.edit') }}" data-record-id="{{ $user->id }}">
                                        {{  trans('misc.button.edit') }}
                                    </button>
                                        <button id="btnEnable_{{ $user->id }}" type="button" class="dropdown-item" data-toggle="modal"
                                                data-target="#confirm"
                                                data-action="enable"
                                                data-title="{{ uctrans('misc.enable') }}"
                                                data-message="{{ trans('misc.sentence.confirm', ['action' => trans('misc.enable')]) }}"
                                                data-confirm="{{ uctrans('misc.enable') }}"
                                                data-confirm-class="btn-success"
                                                data-callback="usercardUpdate"
                                                data-route="{{ route('admin.users.enable', $user->id) }}"
                                                style="display:{{ !$user->disabled === true ? 'none' : 'inline-block' }}">{{  trans('misc.button.enable') }}</button>
                                        <button id="btnDisable_{{ $user->id }}" type="button" class="dropdown-item" data-toggle="modal"
                                                data-target="#confirm"
                                                data-action="disable"
                                                data-title="{{ uctrans('misc.disable') }}"
                                                data-message="{{ trans('misc.sentence.confirm', ['action' => trans('misc.disable')]) }}"
                                                data-confirm="{{ uctrans('misc.disable') }}"
                                                data-confirm-class="btn-danger"
                                                data-callback="usercardUpdate"
                                                data-route="{{ route('admin.users.disable', $user->id) }}"
                                                style="display:{{ $user->disabled === true ? 'none' : 'inline-block' }}">{{  trans('misc.button.disable') }}</button>
                                        <button id="btnDelete_{{ $user->id }}" type="button" class="dropdown-item text-danger" data-toggle="modal"
                                                data-target="#confirm"
                                                data-action="delete"
                                                data-title="{{ uctrans('misc.delete') }}"
                                                data-message="{{ trans('misc.sentence.confirm', ['action' => trans('misc.delete')]) }}"
                                                data-confirm="{{ uctrans('misc.delete') }}"
                                                data-confirm-class="btn-danger"
                                                data-callback="usercardRemove"
                                                data-route="{{ route('admin.users.destroy', $user->id) }}">{{  trans('misc.button.delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text">
                <dl class="row">
                    <dt class="col-sm">{{ uctrans('misc.language', 1) }}</dt>
                    <dd class="col-sm text-right" id="locale_{{ $user->id }}">{{ $user->locale }}</dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm">{{ trans('users.linked_account') }}</dt>
                    <dd class="col-sm text-right" id="account_id_{{ $user->id }}">{{ $user->account->name }}</dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm">{{ trans_choice('misc.role', sizeof($user->roles)) }}</dt>
                    <dd class="col-sm text-right" id="roles_{{ $user->id }}">
                        @if ( sizeof($user->roles) > 0)
                            @foreach ($user->roles as $role)
                                <span class="badge badge-primary ml-1">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="badge badge-secondary">{{ uctrans('misc.none') }}</span>
                        @endif
                    </dd>
                </dl>
            </p>
        </div>
    </div>
</card>