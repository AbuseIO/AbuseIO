<div class="panel panel-default">
    <div class="panel-body">
        {!! Form::open(['route' => 'admin.users.index', 'method' => 'put', 'class' => 'form-inline', 'id' => 'search-form']) !!}
        <div class="form-group label-static">
            {!! Form::label('id', trans('misc.id'), ['class' => 'control-label']) !!}
            {!! Form::number('id', $search_options['id'], ['id' => 'id', 'autocomplete' => 'off', 'class' => 'form-control input-sm', 'data-lpignore' => 'true']) !!}
        </div>
        <div class="form-group label-static">
            {!! Form::label('first_name', trans('users.first_name'), ['class' => 'control-label']) !!}
            {!! Form::text('first_name', $search_options['first_name'], ['id' => 'first_name', 'autocomplete' => 'off', 'class' => 'form-control input-sm', 'data-lpignore' => 'true']) !!}
        </div>
        <div class="form-group label-static">
            {!! Form::label('last_name', trans('users.last_name'), ['class' => 'control-label']) !!}
            {!! Form::text('last_name', $search_options['last_name'], ['id' => 'last_name', 'autocomplete' => 'off', 'class' => 'form-control input-sm', 'data-lpignore' => 'true']) !!}
        </div>
        <div id="dropdown-menu" class="form-group label-static">
            {!! Form::label('account_id', ucfirst(trans_choice('misc.account', 2)), ['class' => 'control-label']) !!}
            {!! Form::select('account_id', $accounts, $search_options['account_id'], ['class' => 'form-control input-sm', 'data-lpignore' => 'true']) !!}
        </div>
        {!! Form::button('<i class="fa fa-search" aria-hidden="true"></i> '. trans('misc.button.search'), ['type' => 'submit', 'class' => 'btn btn-sm btn-success']) !!}
        {!! Form::button('<i class="fa fa-undo" aria-hidden="true"></i> '. trans('misc.button.reset'), ['id' =>'btnResetSubmit', 'type' => 'submit', 'class' => 'btn btn-sm btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>