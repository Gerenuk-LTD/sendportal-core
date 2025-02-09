<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class FileField extends Component
{
    public string $name;

    public string $label;

    /**
     * Create the component instance.
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct(string $name, string $label = '')
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.file-field');
    }
}
