<!-- Video Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('video_id', 'Video Id:') !!}
    {!! Form::text('video_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', 'Path:') !!}
    {!! Form::text('path', null, ['class' => 'form-control']) !!}
</div>

<!-- Available Format Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_format', 'Available Format:') !!}
    {!! Form::text('available_format', null, ['class' => 'form-control']) !!}
</div>

<!-- Selected Format Field -->
<div class="form-group col-sm-6">
    {!! Form::label('selected_format', 'Selected Format:') !!}
    {!! Form::text('selected_format', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Thumbnail Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thumbnail_path', 'Thumbnail Path:') !!}
    {!! Form::text('thumbnail_path', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('downloads.index') !!}" class="btn btn-default text-default">Cancel</a>
</div>
