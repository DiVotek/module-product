<?php

namespace Modules\Product\Components;

use App\View\Components\PageComponent;
use Modules\Product\Models\Product;

class ProductPage extends PageComponent
{
    public function __construct(Product $entity)
    {
        if (empty($entity->template)) {
            $entity->template = setting(config('settings.product.template'), []);
        }

        parent::__construct($entity, 'product::product-component');
    }
}
