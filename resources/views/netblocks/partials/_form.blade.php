<div class="form-group @if ($errors->has('first_ip')) has-error @endif">
    {!! Form::label('first_ip', trans('netblocks.first_ip').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('first_ip', null, ['class' => 'form-control']) !!}
        <p class="help-block">Can be a single IPv4 or IPv6 address. <small>(You can enter CIDR to auto fill 'Last IP address')</small></p>
        @if ($errors->has('first_ip')) <p class="help-block">{{ $errors->first('first_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_ip')) has-error @endif">
    {!! Form::label('last_ip', trans('netblocks.last_ip').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('last_ip', null, ['class' => 'form-control']) !!}
        @if ($errors->has('last_ip')) <p class="help-block">{{ $errors->first('last_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('contact_id')) has-error @endif">
    {!! Form::label('contact_id', trans('misc.contact').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('contact_id', $contact_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('contact_id')) <p class="help-block">{{ $errors->first('contact_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', trans('misc.description').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group">
    {!! Form::label('enabled', trans('misc.status').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('enabled', [1 => trans('misc.enabled'), 0 => trans('misc.disabled')], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
