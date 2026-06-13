<script>
  const uppy_{{ $name }} = new Uppy.Uppy({
      id: 'uppy_{{ $name }}',
      autoProceed: false,
      allowMultipleUploads: @if($multiple ?? true) true @else false @endif,
      debug: false,
      retryUpload: true,
      restrictions: {
          @if($onlyPdf ?? true)
            maxFileSize: 3194304,
            maxNumberOfFiles: {{ $maxFiles ?? 1 }},
            allowedFileTypes: [".pdf"],
          @elseif($onlyImages ?? true)
            maxFileSize: 4194304,
            maxNumberOfFiles: {{ $maxFiles ?? 1 }},
            allowedFileTypes: [".jpg", ".jpeg", ".png", ".gif"],
          @else
            allowedFileTypes: [".pdf", ".jpg", ".jpeg", ".png", ".gif"],
            maxFileSize: 4194304,
            maxNumberOfFiles: {{ $maxFiles ?? 1 }},

          @endif
      },
      meta: {
                  uniqueId: "{{ uniqid()}}",
          },
      infoTimeout: 5000
  })
  .use(Uppy.Dashboard, {
      trigger: '.UppyModalOpenerBtn',
      inline: true,
      target: '.UppyInput_{{ $name }}',
      replaceTargetContent: true,
      showProgressDetails: true,
      @if($onlyPdf ?? true)
        note: '{{ $note ?? "1 pdf file only, up to 3 MB" }}',
        height: 350,
        width: 800,
      @elseif($onlyImages ?? true)
        note: '{{ $note ?? "Upload images" }}',
        height: 400,
        width: 800,
      @else
        note: '{{ $note ?? "Upload files" }}',
        height: 400,
        width: 800,
      @endif
      metaFields: [
          { id: 'name', name: 'Name', placeholder: 'file name' },
          { id: 'caption', name: 'Caption', placeholder: 'describe what the image is about' }
      ],
       
      browserBackButtonClose: false,
      doneButtonHandler: null,
      proudlyDisplayPoweredByUppy: false,
      allowMultipleUploads: @if($multiple ?? true) true @else false @endif,

  })
  
  @if(($onlyImages ?? true) && ($enableWebcam ?? false))
  .use(Uppy.Webcam, { target: Uppy.Dashboard })
  @endif
  
  @if(($onlyImages ?? true) && ($enableImageEditor ?? false))
  .use(Uppy.ImageEditor, { target: Uppy.Dashboard })
  @endif
  
  .use(Uppy.DropTarget, { target: '#{{ $name }}-UppyInput' })
  .use(Uppy.GoldenRetriever)
  
  @if($compress ?? true)
  .use(Uppy.Compressor)
  @endif
  
  uppy_{{ $name }}.use(Uppy.XHRUpload, {
      endpoint: "{{ route('uploads') }}",
      formData: true,
      fieldName: '{{ $name }}',
      @if($multiple === false)
            fieldName: '{{ $name }}',
      @else
        bundle: true,
        fieldName: '{{ $name }}[]',
      @endif
      id:"1",
      headers: {
          "X-CSRF-TOKEN": "{{ csrf_token() }}",
      },
      getResponseData (responseText, response) {
          return {
            folder: responseText,
          }
      }
  });
  
  uppy_{{ $name }}.on('upload-success', (file, response) => {
      const element = document.querySelector("#{{ $name }}");
      if (element) {
          element.value = response.body.folder;
      }
      
      // Trigger change event
      if (element) {
          const event = new Event('change', { bubbles: true });
          element.dispatchEvent(event);
      }
  });
 
  
  // Error handling
  uppy_{{ $name }}.on('error', (error) => {
      console.error('Uppy Error:', error);
      if (typeof Notiflix !== 'undefined') {
          Notiflix.Notify.failure('Upload error: ' + error.message);
      }
  });
  
  uppy_{{ $name }}.on('upload-error', (file, error, response) => {
      console.error('Upload Error:', error);
      if (typeof Notiflix !== 'undefined') {
          Notiflix.Notify.failure(`Failed to upload ${file.name}: ${error.message}`);
      }
  });
  </script>