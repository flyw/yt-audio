<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Entity
 * @package App\Models
 * @version August 7, 2020, 11:26 am CST
 *
 * @property integer $channel_id
 * @property string $title
 * @property string $video_id
 * @property string $thumbnail_source
 * @property string $thumbnail
 * @property string $description
 * @property integer $views_count
 * @property integer $rating_count
 * @property string $rating_average
 * @property integer $is_viewed
 * @property string $published
 * @property string $updated
 * @property integer $viewd_index
 * @property string $audio_file_uri
 * @property integer $split_count
 */
class Entity extends Model
{
    use SoftDeletes;

    public $table = 'entities';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'channel_id',
        'title',
        'video_id',
        'thumbnail_source',
        'thumbnail',
        'description',
        'views_count',
        'rating_count',
        'rating_average',
        'is_viewed',
        'published',
        'updated',
        'viewd_index',
        'audio_file_uri',
        'split_count'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'channel_id' => 'integer',
        'title' => 'string',
        'video_id' => 'string',
        'thumbnail_source' => 'string',
        'thumbnail' => 'string',
        'description' => 'string',
        'views_count' => 'integer',
        'rating_count' => 'integer',
        'rating_average' => 'string',
        'is_viewed' => 'integer',
        'published' => 'datetime',
        'updated' => 'datetime',
        'viewd_index' => 'integer',
        'audio_file_uri' => 'string',
        'split_count' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'channel_id' => 'required',
        'title' => 'required|string|max:191',
        'video_id' => 'required|string|max:191',
        'thumbnail_source' => 'required|string|max:191',
        'thumbnail' => 'required|string|max:191',
        'description' => 'required|string',
        'views_count' => 'required',
        'rating_count' => 'required',
        'rating_average' => 'required|string|max:191',
        'is_viewed' => 'required',
        'published' => 'required',
        'updated' => 'required',
        'viewd_index' => 'nullable',
        'audio_file_uri' => 'nullable|string|max:191',
        'split_count' => 'nullable'
    ];

    public function channel() {
        return $this->belongsTo(Channel::class);
    }


}
