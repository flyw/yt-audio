<?php

namespace App\Repositories;

use App\Models\Channel;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ChannelRepository
 * @package App\Repositories
 * @version August 6, 2020, 11:17 am CST
 *
 * @method Channel findWithoutFail($id, $columns = ['*'])
 * @method Channel find($id, $columns = ['*'])
 * @method Channel first($columns = ['*'])
*/
class ChannelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'channel_id',
        'name'
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
        return Channel::class;
    }
}
