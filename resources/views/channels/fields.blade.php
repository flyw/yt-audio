<!-- Channel Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('channel_id', 'Channel Id:') !!}
    {!! Form::text('channel_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('channels.index') !!}" class="btn btn-default text-default">Cancel</a>
</div>
