### Elastic 搜索功能的 Model 例子 

> 参考文档：https://github.com/babenkoivan/scout-elasticsearch-driver
>
```php
<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Configurators\TemplateIndexConfigurator;
use ScoutElastic\Searchable;

/**
 * Class ElasticExample
 * @package App\ElasticExample
 * @version December 18, 2019, 6:21 am UTC
 *
 * @property string id
 */
class ElasticExample extends Model
{
    use SoftDeletes, Searchable;

    public $table = 'elastic-example';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $indexConfigurator = TemplateIndexConfigurator::class;
    
    /**
     * @var array
     */
    protected $searchRules = [
        //
    ];

    /**
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'name' => [
                'type' => 'text'
            ],
            'created_at'=> [
                'type'=>'date',
                'format'=> 'yyyy-MM-dd HH:mm:ss',
                'fields'=> ['raw'=> ['type'=> 'keyword']]
            ],
            'updated_at'=> [
                'type'=>'date',
                'format'=> 'yyyy-MM-dd HH:mm:ss',
                'fields'=> ['raw'=> ['type'=> 'keyword']]
            ]
        ]
    ];

    
}
```
