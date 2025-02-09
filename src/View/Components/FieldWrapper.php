<?php

declare(strict_types=1);

namespace Sendportal\Base\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class FieldWrapper extends Component
{
    public string $name;

    public string $label;

    public string $wrapperClass;

    /**
     * Create the component instance.
     *
     * @param string $name
     * @param string $label
     * @param string $wrapperClass
     */
    public function __construct(string $name, string $label, string $wrapperClass = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->wrapperClass = $wrapperClass;
    }

    /**
     * @param string $field
     * @return string
     */
    public function errorClass(string $field): string
    {
        if ($errors = session('errors')) {
            return $errors->first($field) ? ' has-error' : '';
        }

        return '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): Closure|string|View
    {
        return view('sendportal::components.field-wrapper');
    }
}
