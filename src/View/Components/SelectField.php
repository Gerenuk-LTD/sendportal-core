<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class SelectField extends Component
{
    public string $name;

    public string $label;

    public array|Collection $options;

    public mixed $value;

    public bool $multiple;

    /**
     * Create the component instance.
     *
     * @param  string  $name
     * @param  string  $label
     * @param  array  $options
     * @param  bool  $multiple
     */
    public function __construct(string $name, string $label = '', array $options = [], $value = null, bool $multiple = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->value = $value;
        $this->multiple = $multiple;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isSelected($key): bool
    {
        if ($this->multiple) {
            if ($this->value instanceof Collection) {
                return $this->value->has($key);
            } elseif (is_array($this->value)) {
                return array_key_exists($key, $this->value);
            }
        }

        return $key == $this->value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.select-field');
    }
}
