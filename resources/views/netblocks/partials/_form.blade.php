<div class="form-group @if ($errors->has('first_ip')) has-error @endif">
    <label for="first_ip" class="col-sm-2 control-label">{{ trans('netblocks.first_ip') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="first_ip" id="first_ip" value="{{ old('first_ip', isset($netblock) ? $netblock->first_ip : null) }}" class="form-control">
        <p class="help-block">Can be a single IPv4 or IPv6 address. <small>(You can enter CIDR to auto fill 'Last IP address')</small></p>
        @if ($errors->has('first_ip')) <p class="help-block">{{ $errors->first('first_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('last_ip')) has-error @endif">
    <label for="last_ip" class="col-sm-2 control-label">{{ trans('netblocks.last_ip') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="last_ip" id="last_ip" value="{{ old('last_ip', isset($netblock) ? $netblock->last_ip : null) }}" class="form-control">
        @if ($errors->has('last_ip')) <p class="help-block">{{ $errors->first('last_ip') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('contact_id')) has-error @endif">
    <label for="contact_id" class="col-sm-2 control-label">{{ trans('misc.contact') }}:</label>
    <div class="col-sm-10">
        <select name="contact_id" id="contact_id" class="form-control">
            @foreach($contact_selection as $id => $name)
                <option value="{{ $id }}" @if(old('contact_id', $selected) == $id) selected @endif>{{ $name }}</option>
            @endforeach
        </select>
        @if ($errors->has('contact_id')) <p class="help-block">{{ $errors->first('contact_id') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    <label for="description" class="col-sm-2 control-label">{{ trans('misc.description') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="description" id="description" value="{{ old('description', isset($netblock) ? $netblock->description : null) }}" class="form-control">
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <label for="enabled" class="col-sm-2 control-label">{{ trans('misc.status') }}:</label>
    <div class="col-sm-10">
        <select name="enabled" id="enabled" class="form-control">
            <option value="1" @if(old('enabled', isset($netblock) ? $netblock->enabled : null) == 1) selected @endif>{{ trans('misc.enabled') }}</option>
            <option value="0" @if(old('enabled', isset($netblock) ? $netblock->enabled : null) == 0) selected @endif>{{ trans('misc.disabled') }}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
    </div>
</div>
