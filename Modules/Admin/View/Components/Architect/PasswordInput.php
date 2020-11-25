<?php

namespace Modules\Admin\View\Components\Architect;

use Illuminate\View\Component;

class PasswordInput extends Component
{
    public $label;
    public $attribute;
    public $model;
    public $confirmed;
    public $confirmedLabel;
    public $hint;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $attribute, $model = null, $confirmed = true, $confirmedLabel = null, $hint = null)
    {
        $this->label = $label;
        $this->attribute = $attribute;
        $this->model = $model;
        $this->confirmed = $confirmed;
        $this->confirmedLabel = $confirmedLabel ?? 'Confirm '.$label;
        $this->hint = $hint;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin::components.architect.password-input');
    }
}
