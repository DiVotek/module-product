<?php

namespace Modules\Product\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Product\Models\Product;

class ProductComponent extends Component
{
    public Product $product;

    public $quantity = 1;
    public $options;
    public array $selectedOptions;
    public $price;
    public $breadcrumbs;
    public $productAttributes;

    public function mount(Product $entity, $breadcrumbs = [])
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->product = $entity;
        $this->options = [];
        $this->selectedOptions = [];
        if (module_enabled('Options')) {
            $this->options = \Modules\Options\Models\Option::query()->whereIn('id', $this->product->optionValues()->pluck('option_id'))->get();
            foreach ($this->options as $option) {
                if ($option->default_value) {
                    $this->selectedOptions[$option->id] = $option->default_value;
                }
            }
        }
        $this->productAttributes = [];
    }
    #[Computed]
    public function price()
    {
        $price = $this->product->price;
        if (module_enabled('Options')) {
            $options = $this->product->optionValues()->withPivot('price', 'sign')->whereIn('option_value_id', array_values($this->selectedOptions))->get();
            foreach ($options as $option) {
                if ($option->pivot->sign == '+') {
                    $price += $option->pivot->price;
                } else {
                    $price -= $option->pivot->price;
                }
            }
        }
        return $price;
    }
    public function changedOptions()
    {
        return $this->price();
    }

    public function render()
    {
        return view('template::' . setting(config('settings.product.design'), 'product.default'));
    }

    public function changeQuantity($quantity)
    {
        $this->quantity = $quantity;
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
    }

    public function addToCart()
    {
        if (module_enabled('Order')) {
            $validation = [];
            if (module_enabled('Options')) {
                foreach ($this->options as $option) {
                    if ($option->required) {
                        $validation['selectedOptions.' . $option->id] = 'required';
                    }
                }
            }
            if (!empty($validation)) {
                $this->validate($validation);
            }
            for ($i = 0; $i < $this->quantity; $i++) {
                $this->dispatch('addToCart', $this->product->id, $this->selectedOptions);
            }
            $this->quantity = 1;
        }
    }
}
