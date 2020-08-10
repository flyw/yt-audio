<?php

namespace App\Repositories;

use App\Models\Entity;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EntityRepository
 * @package App\Repositories
 * @version August 6, 2020, 11:17 am CST
 *
 * @method Entity findWithoutFail($id, $columns = ['*'])
 * @method Entity find($id, $columns = ['*'])
 * @method Entity first($columns = ['*'])
*/
class EntityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'channel_id',
        'title',
        'video_id',
        'published',
        'updated',
        'thumbnail',
        'description',
        'views_count',
        'rating_count',
        'rating_average',
        'is_viewed',
        'viewd_index'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Entity::class;
    }
}
