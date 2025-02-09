<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class CheckboxField extends Component
{
    public string $name;

    public string $label;

    public mixed $value;

    public bool $checked;

    /**
     * Create the component instance.
     *
     * @param string $name
     * @param string $label
     * @param int $value
     * @param bool $checked
     */
    public function __construct(string $name, string $label = '', int $value = 1, bool $checked = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->checked = $checked;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Closure|string|View
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.checkbox-field');
    }
}
