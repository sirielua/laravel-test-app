<?php

namespace Modules\Admin\View\Components\Architect;

use Illuminate\View\Component;

class RadioInput extends Component
{
    public $label;
    public $attribute;
    public $options;
    public $default;
    public $model;
    public $hint;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $attribute, $options = [], $default = null, $model = null, $hint = null)
    {
        $this->label = $label;
        $this->attribute = $attribute;
        $this->options = $options;
        $this->default = $default;
        $this->model = $model;
        $this->hint = $hint;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin::_components.architect.radio-input');
    }
}
