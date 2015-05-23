<div class="form-group">
    {!! Form::label('name', 'Domain name:') !!}
    {!! Form::text('name') !!}
</div>
<div class="form-group">
    {!! Form::label('contact_id', 'Contact:') !!}
    {!! Form::select('contact_id', $contact_selection, $selected) !!}
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}
</div>