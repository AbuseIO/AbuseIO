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
    {!! Form::label('creator_id', trans('misc.creator').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('creator_id', $account_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('creator_id')) <p class="help-block">{{ $errors->first('creator_id') }}</p> @endif
    </div>
</div>
@else
{!! Form::hidden('creator_id', $auth_user->account->id) !!}
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
<div class="form-group @if ($errors->has('mail_custom_template')) has-error @endif">
    {!! Form::label('mail_custom_template', trans('brands.mail_custom_template').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::hidden('mail_custom_template', $mail_custom_template) !!}
        {!! Form::checkbox('mail_custom_templatedummy', true, $mail_custom_template, ['id' => 'mail_custom_templatedummy']) !!}
        @if ($errors->has('mail_custom_template')) <p class="help-block">{{ $errors->first('mail_custom_template') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10 mail_template" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.mail_template_plain') }}</h3>
            </div>
            <div class="panel-body">
                @if ($errors->has('mail_template_plain')) <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> {{$errors->first('mail_template_plain')}}</p> @endif
                {!! Form::textarea('mail_template_plain', htmlentities($templates['plain_mail']), ['id' => 'mail_template_plain', 'style' => 'width: 100%']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10 mail_template" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.mail_template_html') }}</h3>
            </div>
            <div class="panel-body">
                @if ($errors->has('mail_template_html')) <p class="help-block has-error"><span class="glyphicon glyphicon-exclamation-sign"></span> {{$errors->first('mail_template_html')}}</p> @endif
                {!! Form::textarea('mail_template_html', htmlentities($templates['html_mail']), ['id' => 'mail_template_html', 'style' => 'width: 100%']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('ash_custom_template')) has-error @endif">
    {!! Form::label('ash_custom_template', trans('brands.ash_custom_template').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::hidden('ash_custom_template', $ash_custom_template) !!}
        {!! Form::checkbox('ash_custom_templatedummy', true, $ash_custom_template, ['id' => 'ash_custom_templatedummy']) !!}
        @if ($errors->has('ash_custom_template')) <p class="help-block">{{ $errors->first('ash_custom_template') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10 ash_template" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.ash_template') }}</h3>
            </div>
            <div class="panel-body">
                @if ($errors->has('ash_template')) <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> {{$errors->first('ash_template')}}</p> @endif
                {!! Form::textarea('ash_template', htmlentities($templates['ash']), ['id' => 'ash_template', 'style' => 'width: 100%']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>

@section('extrajs')
<script>
    /* mail/ash template handling */

    if ({{ ($mail_custom_template ? 1 : 0) }} == 0) {
        $('.mail_template').hide();
        $('#mail_custom_template').val(false);
    } else {
        $('#mail_custom_template').val(true);
    }

    if ({{ ($ash_custom_template ? 1 : 0) }} == 0) {
        $('.ash_template').hide();
        $('#ash_custom_template').val(false);
    } else {
        $('#ash_custom_template').val(true);
    }

    $('input:checkbox[name="mail_custom_templatedummy"]').change(function() {
        $('#mail_custom_template').val($(this).is(':checked'));
        $('.mail_template').toggle();
    });

    $('input:checkbox[name="ash_custom_templatedummy"]').change(function() {
        $('#ash_custom_template').val($(this).is(':checked'));
        $('.ash_template').toggle();
    });


</script>
@stop
