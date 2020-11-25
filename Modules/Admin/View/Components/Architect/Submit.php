<?php

namespace Modules\Admin\View\Components\Architect;

use Illuminate\View\Component;

class Submit extends Component
{
    public $label;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = 'Submit')
    {
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin::components.architect.submit');
    }
}
