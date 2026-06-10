<?php

namespace App\View\Components;

use Illuminate\View\Component;

class uppyDocPdfFileScript extends Component
{

    public string $name;
    public string $onlyImages;
    public string $onlyPdf;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $onlyPdf = "false", string $onlyImages = "false")
    {
        //
        $this->name = $name;
        $this->onlyImages = $onlyImages;
        $this->onlyPdf = $onlyPdf;
        
       
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.uppy-doc-pdf-file-script');
    }
}
