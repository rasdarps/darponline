<x-input-wrapper wrapper-class="{{ $inputWrapperClass }}">

  <label @if($name != "tailors_certificates") class="form-label h4" @else  class="form-label" @endif for="{{ $name }}"> {{ $label }} @if($name != "tailors_certificates" && $name != "certificate")<span class="text-danger font-weight-bold">*</span>@endif </label>

    <div id="{{ $name }}-UppyInput" class="p-0 UppyInput_{{ $name }}"></div>
        
   
    
    <input hidden id="{{ $name }}" value = "" name="{{ $name }}" type="text">
  
  </x-input-wrapper>