<?php

namespace Modules\Admin\View\Components\Architect;

use Illuminate\View\Component;

class TextInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        // <x-architect::text-input/>

        return view('admin::components.architect.text-input');
    }
}
