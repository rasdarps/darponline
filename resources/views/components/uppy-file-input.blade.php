@props([
    'name' => '',
    'label' => '',
    'inputWrapperClass' => '',
    'multiple' => false,
    'maxFiles' => 1,
    'onlyImages' => false,
    'onlyPdf' => false,
    'note' => '',
    'enableWebcam' => false,
    'enableImageEditor' => false,
    'compress' => true,
    'required' => false,
    'value' => null,
])

<x-input-wrapper wrapper-class="{{ $inputWrapperClass }}">
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if($required)
        <span class="text-danger font-weight-bold">*</span>
        @endif
    </label>

    <div id="{{ $name }}-UppyInput" class="p-0 UppyInput_{{ $name }}" 
         style="min-height: {{ $multiple ? '300px' : '200px' }};"></div>
    
    <input hidden id="{{ $name }}" name="{{ $name }}" type="text" value="{{ $value }}">
    
    @if($note)
    <small class="text-muted">{{ $note }}</small>
    @endif
    <br>
    <span class="text-danger error-text" data-field="{{ $name }}"></span>

</x-input-wrapper>

@push('scripts')


@include('components.uppy-file-script', [
    'name' => $name,
    'multiple' => $multiple,
    'maxFiles' => $maxFiles,
    'onlyPdf' => $onlyPdf,
    'onlyImages' => $onlyImages,
    'note' => $note,
    'enableWebcam' => $enableWebcam,
    'enableImageEditor' => $enableImageEditor,
    'compress' => $compress
])
@endpush