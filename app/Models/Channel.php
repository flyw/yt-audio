<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Channel
 * @package App\Models
 * @version August 6, 2020, 11:33 am CST
 *
 * @property string $channel_id
 * @property string $name
 * @property string $published
 */
class Channel extends Model
{
    use SoftDeletes;

    public $table = 'channels';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'channel_id',
        'name',
        'published'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'channel_id' => 'string',
        'name' => 'string',
        'published' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'channel_id' => 'required|string|max:191',
        'name' => 'nullable|string|max:191',
        'published' => 'nullable'
    ];

    public function entities() {
        return $this->hasMany(Entity::class);
    }


}
