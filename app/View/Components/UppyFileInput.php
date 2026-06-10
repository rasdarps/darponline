<?php

namespace App\View\Components;

use Illuminate\View\Component;

class uppyFileInput extends Component
{

    public string $name;
    public string $label;
    public string $inputWrapperClass;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name,  string $label = null, string $inputWrapperClass)
    {
        //
        $this->label = $label;
        $this->name = $name;
        $this->inputWrapperClass = $inputWrapperClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.uppy-file-input');
    }
}
