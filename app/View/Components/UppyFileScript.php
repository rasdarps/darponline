<?php

namespace App\View\Components;

use Illuminate\View\Component;

class uppyFileScript extends Component
{

    public string $name;
    public bool $enableWebcam, $enableImageEditor, $multiple, $onlyImages, $onlyPdf;
    public int $maxFiles;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, bool $onlyPdf = false, bool $onlyImages = false, 
    bool $enableWebcam = false, bool $enableImageEditor = false, bool $multiple = false, int $maxFiles = 1)
    {
        //
        $this->name = $name;
        $this->onlyImages = $onlyImages;
        $this->onlyPdf = $onlyPdf;
        $this->enableWebcam = $enableWebcam;
        $this->enableImageEditor = $enableImageEditor;
        $this->multiple = $multiple;
        $this->maxFiles = $maxFiles;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.uppy-file-script');
    }
}
