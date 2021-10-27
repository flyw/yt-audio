<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Download
 * @package App\Models
 * @version October 24, 2021, 1:42 pm CST
 *
 * @property string $video_id
 * @property string $path
 * @property string $available_format
 * @property string $selected_format
 * @property string $title
 * @property string $thumbnail_path
 */
class Download extends Model
{
//    use SoftDeletes;

    public $table = 'downloads';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


//    protected $dates = ['deleted_at'];


    public $fillable = [
        'video_id',
        'path',
        'available_format',
        'selected_format',
        'title',
        'thumbnail_path'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'video_id' => 'string',
        'path' => 'string',
        'available_format' => 'string',
        'selected_format' => 'string',
        'title' => 'string',
        'thumbnail_path' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'video_id' => 'required|string|max:191',
        'path' => 'required|string|max:191',
        'available_format' => 'required|string|max:191',
        'selected_format' => 'required|string|max:191',
        'title' => 'required|string|max:191',
        'thumbnail_path' => 'required|string|max:191'
    ];


}
