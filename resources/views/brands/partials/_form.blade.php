<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name" class="col-sm-2 control-label">{{ trans('misc.name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="name" id="name" value="{{ old('name', isset($brand) ? $brand->name : null) }}"
               class="form-control">
        @if ($errors->has('name'))
            <p class="help-block">{{ $errors->first('name') }}</p>
        @endif
    </div>
</div>
<div class="form-group @if ($errors->has('company_name')) has-error @endif">
    <label for="company_name" class="col-sm-2 control-label">{{ trans('misc.company_name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="company_name" id="company_name"
               value="{{ old('company_name', isset($brand) ? $brand->company_name : null) }}" class="form-control">
        @if ($errors->has('company_name'))
            <p class="help-block">{{ $errors->first('company_name') }}</p>
        @endif
    </div>
</div>
<div class="form-group @if ($errors->has('introduction_text')) has-error @endif">
    <label for="introduction_text" class="col-sm-2 control-label">{{ trans('misc.text') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="introduction_text" id="introduction_text"
               value="{{ old('introduction_text', isset($brand) ? $brand->introduction_text : null) }}"
               class="form-control">
        @if ($errors->has('introduction_text'))
            <p class="help-block">{{ $errors->first('introduction_text') }}</p>
        @endif
    </div>
</div>
@if ($auth_user->account->isSystemAccount())
    <div class="form-group @if ($errors->has('creator_id')) has-error @endif">
        <label for="creator_id" class="col-sm-2 control-label">{{ trans('misc.creator') }}:</label>
        <div class="col-sm-10">
            <select name="creator_id" id="creator_id" class="form-control">
                @foreach($account_selection as $id => $name)
                    <option value="{{ $id }}"
                            @if(old('creator_id', $selected) == $id) selected @endif>{{ $name }}</option>
                @endforeach
            </select>
            @if ($errors->has('creator_id'))
                <p class="help-block">{{ $errors->first('creator_id') }}</p>
            @endif
        </div>
    </div>
@else
    <input type="hidden" name="creator_id" value="{{ $auth_user->account->id }}">
@endif
<div class="form-group @if ($errors->has('logo')) has-error @endif">
    <label for="logo" class="col-sm-2 control-label">{{ trans('brands.logo') }}:</label>
    <div class="col-sm-10">
        @if ($brand)
            @if ($brand->logo)
                <img src="/admin/logo/{{ $brand->id }}"/>
            @endif
        @endif
        <input type="file" name="logo" id="logo">
        @if ($errors->has('logo'))
            <p class="help-block">{{ $errors->first('logo') }}</p>
        @endif
    </div>
</div>
<div class="form-group @if ($errors->has('mail_custom_template')) has-error @endif">
    <label for="mail_custom_template" class="col-sm-2 control-label">{{ trans('brands.mail_custom_template') }}:</label>
    <div class="col-sm-10">
        <input type="hidden" name="mail_custom_template" id="mail_custom_template"
               value="{{ old('mail_custom_template', $mail_custom_template) ? 'true' : 'false' }}">
        <input type="checkbox" name="mail_custom_templatedummy" id="mail_custom_templatedummy" value="1"
               @if(old('mail_custom_template', $mail_custom_template)) checked @endif>
        @if ($errors->has('mail_custom_template'))
            <p class="help-block">{{ $errors->first('mail_custom_template') }}</p>
        @endif
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
                <textarea name="mail_template_plain" id="mail_template_plain" style="width: 100%">{!! old('mail_template_plain', html_entity_decode($templates['plain_mail'])) !!}</textarea>
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
                @if ($errors->has('mail_template_html'))
                    <p class="help-block has-error"><span
                                class="glyphicon glyphicon-exclamation-sign"></span> {{$errors->first('mail_template_html')}}
                    </p>
                @endif
                <textarea name="mail_template_html" id="mail_template_html"
                          style="width: 100%">{!! old('mail_template_html', html_entity_decode($templates['html_mail'])) !!}</textarea>
            </div>
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('ash_custom_template')) has-error @endif">
    <label for="ash_custom_template" class="col-sm-2 control-label">{{ trans('brands.ash_custom_template') }}:</label>
    <div class="col-sm-10">
        <input type="hidden" name="ash_custom_template" id="ash_custom_template"
               value="{{ old('ash_custom_template', $ash_custom_template) ? 'true' : 'false' }}">
        <input type="checkbox" name="ash_custom_templatedummy" id="ash_custom_templatedummy" value="1"
               @if(old('ash_custom_template', $ash_custom_template)) checked @endif>
        @if ($errors->has('ash_custom_template'))
            <p class="help-block">{{ $errors->first('ash_custom_template') }}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10 ash_template" style="padding-left: 0;">
        <div class="panel panel-default panel_info">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">{{ trans('brands.ash_template') }}</h3>
            </div>
            <div class="panel-body">
                @if ($errors->has('ash_template'))
                    <p class="help-block"><span
                                class="glyphicon glyphicon-exclamation-sign"></span> {{$errors->first('ash_template')}}
                    </p>
                @endif
                <textarea name="ash_template" id="ash_template"
                          style="width: 100%">{!! old('ash_template', htmlspecialchars(html_entity_decode($templates['ash']))) !!}</textarea>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
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

        $('input:checkbox[name="mail_custom_templatedummy"]').change(function () {
            $('#mail_custom_template').val($(this).is(':checked'));
            $('.mail_template').toggle();
        });

        $('input:checkbox[name="ash_custom_templatedummy"]').change(function () {
            $('#ash_custom_template').val($(this).is(':checked'));
            $('.ash_template').toggle();
        });


    </script>
@stop
