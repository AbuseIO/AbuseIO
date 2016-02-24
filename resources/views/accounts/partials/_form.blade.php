<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('misc.name').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', trans('misc.description').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('brand_id')) has-error @endif">
    {!! Form::label('brand_id', trans_choice('misc.brands', 1).':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('brand_id', $brand_selection, $selected, ['class' => 'form-control']) !!}
        @if ($errors->has('brand_id')) <p class="help-block">{{ $errors->first('brand_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('disable')) has-error @endif">
    {!! Form::label('disabled', trans('misc.disabled').':', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::hidden('disabled', 'false') !!}
        {!! Form::checkbox('disableddummy', true, $disabled_checked) !!}
        @if ($errors->has('disabled')) <p class="help-block">{{ $errors->first('disabled') }}</p> @endif
    </div>
</div>
<script>
    $('input:checkbox[name="disableddummy"]').change(function() {
        $('#disabled').val($(this).is(':checked'));
    });
</script>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submit_text, ['class'=>'btn btn-success']) !!}
        {!! link_to(URL::previous(), trans('misc.button.cancel'), ['class' => 'btn btn-default']) !!}
    </div>
</div>
