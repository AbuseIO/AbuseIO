<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name" class="col-sm-2 control-label">{{ trans('domains.domainname') }}:</label>
    <div class="col-sm-10">
        <input type="text" name="name" id="name" value="{{ old('name', isset($domain) ? $domain->name : null) }}" class="form-control" />
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('contact_id')) has-error @endif">
    <label for="contact_id" class="col-sm-2 control-label">{{ trans('misc.contact') }}:</label>
    <div class="col-sm-10">
        <select name="contact_id" id="contact_id" class="form-control">
            <option value="">--</option>
            @foreach ($contact_selection as $id => $name)
                <option value="{{ $id }}" @if (old('contact_id', isset($selected) ? $selected : null) == $id) selected @endif>{{ $name }}</option>
            @endforeach
        </select>
        @if ($errors->has('contact_id')) <p class="help-block">{{ $errors->first('contact_id') }}</p> @endif
    </div>
</div>
<div class="form-group">
    <label for="enabled" class="col-sm-2 control-label">{{ trans('misc.status') }}:</label>
    <div class="col-sm-10">
        <select name="enabled" id="enabled" class="form-control">
            <option value="1" @if (old('enabled', isset($domain) ? $domain->enabled : null) == 1) selected @endif>{{ trans('misc.enabled') }}</option>
            <option value="0" @if (old('enabled', isset($domain) ? $domain->enabled : null) == 0) selected @endif>{{ trans('misc.disabled') }}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">{{ $submit_text }}</button>
        <a href="{{ URL::previous() }}" class="btn btn-default">{{ trans('misc.button.cancel') }}</a>
    </div>
</div>
