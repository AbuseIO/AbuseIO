<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('misc.name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
       {!! Form::text('name', null, ['class' => 'form-control']) !!}
       @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('company_name')) has-error @endif">
    {!! Form::label('company_name', trans('misc.company_name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('company_name')) <p class="help-block">{{ $errors->first('company_name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('introduction_text')) has-error @endif">
    {!! Form::label('introduction_text', trans('misc.text').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('introduction_text', null, ['class' => 'form-control']) !!}
        @if ($errors->has('introduction_text')) <p class="help-block">{{ $errors->first('introduction_text') }}</p> @endif
    </div>
</div>
@if ($auth_user->account->isSystemAccount())
<div class="form-group @if ($errors->has('account_id')) has-error @endif">
    {!! Form::label('account_id', trans_choice('misc.accounts', 1).':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('account_id', $account_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('account_id')) <p class="help-block">{{ $errors->first('account_id') }}</p> @endif
    </div>
</div>
@endif
<div class="form-group @if ($errors->has('introduction_text')) has-error @endif">
    {!! Form::label('logo', trans('brands.logo').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        @if ($brand)
            @if ($brand->logo)
                <img src="/admin/logo/{{ $brand->id }}" />
            @endif
        @endif
        {!! Form::file('logo') !!}
        @if ($errors->has('logo')) <p class="help-block">{{ $errors->first('logo') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
