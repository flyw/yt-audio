<?php

namespace App\Repositories;

use App\Models\Download;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DownloadRepository
 * @package App\Repositories
 * @version October 24, 2021, 1:42 pm CST
 *
 * @method Download findWithoutFail($id, $columns = ['*'])
 * @method Download find($id, $columns = ['*'])
 * @method Download first($columns = ['*'])
*/
class DownloadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'video_id',
        'path',
        'available_format',
        'selected_format',
        'title',
        'thumbnail_path'
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
        return Download::class;
    }
}
