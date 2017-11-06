<card class="col-sm-6 col-md-4 col-lg-3 mb-4" id="card_{{ $user->id }}">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <div class="row justify-content-between">
                <div class="col-10">
                    <h4 class="card-title">{{ $user->fullName() }}</h4>
                </div>
                <div class="col-2 text-right">
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary bmd-btn-icon dropdown-toggle" id="userOptionsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userOptionsMenu">
                                    {!! link_to_route('admin.users.edit', trans('misc.button.edit'), $user->id, ['class' => 'dropdown-item']) !!}
                                    @if ( $user->disabled )
                                        {!! link_to_route('admin.users.enable', trans('misc.button.enable'), $user->id, ['class' => 'dropdown-item']) !!}
                                    @else
                                        {!! link_to_route('admin.users.disable', trans('misc.button.disable'), $user->id, ['class' => 'dropdown-item']) !!}
                                    @endif
                                    <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target-route="{{ route('admin.users.destroy', $user->id) }}" data-record-id="{{ $user->id }}" data-target="#confirmDelete">
                                        {{  trans('misc.button.delete') }}
                                    </button>
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
                <dt class="col-sm">{{ trans('misc.status') }}</dt>
                <dd class="col-sm text-right">{{ $user->disabled ? trans('misc.disabled') : trans('misc.enabled') }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm">{{ trans('users.linked_account') }}</dt>
                <dd class="col-sm text-right">{{ $user->account->name }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm">{{ trans_choice('misc.role', sizeof($user->roles)) }}</dt>
                <dd class="col-sm text-right">
                    @if ( sizeof($user->roles) > 0)
                        @foreach ($user->roles as $role)
                            <span class="badge badge-primary">{{ $role->name }}</span>
                        @endforeach
                    @else
                        <span class="badge badge-secondary">None</span>
                    @endif
                </dd>
            </dl>
            </p>
        </div>
    </div>
</card>