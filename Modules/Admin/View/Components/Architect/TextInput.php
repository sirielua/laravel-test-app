<?php

namespace Modules\Admin\View\Components\Architect;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $label;
    public $attribute;
    public $model;
    public $hint;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $attribute, $model = null, $hint = null)
    {
        $this->label = $label;
        $this->attribute = $attribute;
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
        return view('admin::_components.architect.text-input');
    }
}
