<div class="form-group">
    {!! Form::label('first_ip', 'First IP address:') !!}
    {!! Form::text('first_ip') !!}
</div>
<div class="form-group">
    {!! Form::label('last_ip', 'Last IP address:') !!}
    {!! Form::text('last_ip') !!}
</div>
<div class="form-group">
    {!! Form::label('contact_id', 'Contact:') !!}
    {!! Form::select('contact_id', $contact_selection, $selected) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description') !!}
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}
</div>
