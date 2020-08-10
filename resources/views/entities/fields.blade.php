<!-- Channel Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('channel_id', 'Channel Id:') !!}
    {!! Form::number('channel_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Video Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('video_id', 'Video Id:') !!}
    {!! Form::text('video_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Published Field -->
<div class="form-group col-sm-6">
    {!! Form::label('published', 'Published:') !!}
    {!! Form::text('published', null, ['class' => 'form-control']) !!}
</div>

<!-- Updated Field -->
<div class="form-group col-sm-6">
    {!! Form::label('updated', 'Updated:') !!}
    {!! Form::text('updated', null, ['class' => 'form-control']) !!}
</div>

<!-- Thumbnail Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    {!! Form::text('thumbnail', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Views Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('views_count', 'Views Count:') !!}
    {!! Form::number('views_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Rating Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rating_count', 'Rating Count:') !!}
    {!! Form::number('rating_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Rating Average Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rating_average', 'Rating Average:') !!}
    {!! Form::number('rating_average', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Viewed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_viewed', 'Is Viewed:') !!}
    {!! Form::number('is_viewed', null, ['class' => 'form-control']) !!}
</div>

<!-- Viewd Index Field -->
<div class="form-group col-sm-6">
    {!! Form::label('viewd_index', 'Viewd Index:') !!}
    {!! Form::number('viewd_index', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('entities.index') !!}" class="btn btn-default text-default">Cancel</a>
</div>
