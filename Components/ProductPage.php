<?php

namespace Modules\Product\Components;

use App\View\Components\PageComponent;
use Modules\Product\Models\Product;

class ProductPage extends PageComponent
{
    public function __construct(Product $entity)
    {
        if (empty($entity->template)) {
            $entity->template =  setting(config('settings.product.design'), []);
        }
        $component = setting(config('settings.product.design'), 'Base');
        $component = 'template.' . strtolower(template()) . '.pages.product.' . strtolower($component);

        parent::__construct($entity, $component);
    }
}
