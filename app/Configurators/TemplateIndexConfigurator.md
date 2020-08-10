### Elastic 搜索功能的 IndexConfigurator 例子

> 复制并修改这个例子，每一个Model 对应一个 IndexConfigurator
>
> 参考文档：https://github.com/babenkoivan/scout-elasticsearch-driver

```php
<?php

namespace App\Configurators;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class TemplateIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'elastic-index-name';

    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}
```
