<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class SubmitButton extends Component
{
    public string $label;

    /**
     * Create the component instance.
     *
     * @param  string  $label
     * @return void
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.submit-button');
    }
}
