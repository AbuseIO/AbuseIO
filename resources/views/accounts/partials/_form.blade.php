<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name" class="col-sm-2 control-label">{{ trans('misc.name') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="name" id="name" value="{{ old('name', isset($account) ? $account->name : null) }}" class="form-control">
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    <label for="description" class="col-sm-2 control-label">{{ trans('misc.description') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="description" id="description" value="{{ old('description', isset($account) ? $account->description : null) }}" class="form-control">
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('brand_id')) has-error @endif">
    <label for="brand_id" class="col-sm-2 control-label">{{ trans_choice('misc.brands', 1) }}:</label>
    <div class="col-sm-10">
        <select name="brand_id" id="brand_id" class="form-control">
            @foreach($brand_selection as $id => $name)
                <option value="{{ $id }}" @if(old('brand_id', $selected) == $id) selected @endif>{{ $name }}</option>
            @endforeach
        </select>
        @if ($errors->has('brand_id')) <p class="help-block">{{ $errors->first('brand_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('disable')) has-error @endif">
    <label for="disabled" class="col-sm-2 control-label">{{ trans('misc.disabled') }}:</label>
    <div class="col-sm-10">
        <input type="hidden" name="disabled" id="disabled" value="false">
        <input type="checkbox" name="disableddummy" value="1" @if($disabled_checked) checked @endif>
        @if ($errors->has('disabled')) <p class="help-block">{{ $errors->first('disabled') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <label for="apikey" class="col-sm-2 control-label">{{ trans('accounts.api_key') }}:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" name="token" id="apikey" value="{{ old('token', isset($account) ? $account->token : null) }}" class="form-control">
            <span class="input-group-btn">
                <button id="refreshApiKey" title="{{ trans('misc.refresh') }}" class="btn"  type="button"><i class="glyphicon glyphicon-refresh"></i></button>
            </span>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('input:checkbox[name="disableddummy"]').change(function() {
            $('#disabled').val($(this).is(':checked'));
        });
        $(document).on('click', '#refreshApiKey', function() {
            $.post('/admin/apikey', function(data) {
                $('#apikey').val(data.data);
            })
                .fail(function(data) {
                    alert('Error, please look in your console!');
                    console.dir(data);
                });
        });
    </script>
@stop
