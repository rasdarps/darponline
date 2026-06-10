<x-input-wrapper wrapper-class="{{ $inputWrapperClass }}">

  <label class="form-label" for="{{ $name }}"> {{ $label }}<span class="text-danger font-weight-bold">*</span></label>

    <div id="{{ $name }}-UppyInput" class="p-0 UppyInput_{{ $name }}"></div>
        
   
    
    <input hidden id="{{ $name }}" name="{{ $name }}" type="text">
  
  </x-input-wrapper>