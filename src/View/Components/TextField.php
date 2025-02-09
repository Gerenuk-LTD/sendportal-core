<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class TextField extends Component
{
    public string $name;

    public string $label;

    public string $type;

    public mixed $value;

    /**
     * Create the component instance.
     *
     * @param  string  $name
     * @param  string  $label
     * @param  string  $type
     * @param  mixed  $value
     */
    public function __construct(string $name, string $label = '', string $type = 'text', mixed $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.text-field');
    }
}
