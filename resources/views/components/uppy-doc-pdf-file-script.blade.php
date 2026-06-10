<script>



  const uppy_{{ $name }} = new Uppy.Uppy({
      id: 'uppy_{{ $name }}',
      autoProceed: false,
      
      debug: false,
      retryUpload: true,
      restrictions: {
      
        maxFileSize: 4194304,
         
        @if($name == "profile_pic")
          maxNumberOfFiles: 1,
          allowMultipleUploads: false,
          allowedFileTypes: [".png", ".jpg", ".jpeg"],
        @elseif($name == "employee_documents")
          maxNumberOfFiles: 1,
          allowMultipleUploads: false,
          allowedFileTypes: [".pdf", ".png", ".jpg", ".jpeg"],
        @else
          allowMultipleUploads: true,
          minNumberOfFiles: 1,
          allowedFileTypes: [".pdf"],
        @endif

      },

      meta: {
              business_name: "{{ uniqid()}}",
      },

      
      infoTimeout: 5000
  })
  .use(Uppy.Dashboard, {
    trigger: '.UppyModalOpenerBtn',
    inline: true,
    target: '.UppyInput_{{ $name }}',
    replaceTargetContent: true,
    showProgressDetails: true,
  

    @if($name == "employee_documents")
     note: 'PDF or Image files (PNG, JPG, JPEG), up to 4 MB. One file per document type.',
    @elseif($name == "profile_pic")
     note: 'Only 1 file, up to 4 MB.',
    @else
     note: 'PDF or doc file only, up to 4 MB. You can upload multiple files.',
    @endif
    height: 350,
        width: 800,
    

  
    @if($name == "profile_pic")
    metaFields: [
      { id: 'name', name: 'Name', placeholder: 'file name' },
      { id: 'caption', name: 'Caption', placeholder: 'describe what the image is about' }
    ],
    @endif
     // showRemoveButtonAfterComplete: true,
     browserBackButtonClose: false,
     doneButtonHandler: null,
     proudlyDisplayPoweredByUppy: false,
  
  })
  .use(Uppy.GoldenRetriever)
  @if($name == "profile_pic")
  .use(Uppy.ImageEditor, { target: Uppy.Dashboard })
  @endif

  .use(Uppy.DropTarget, {target: 'uppy_{{ $name }}' })

  .use(Uppy.Compressor)
  uppy_{{ $name }}.use(Uppy.XHRUpload, {
      endpoint: "{{ route('uploads') }}",
          
          formData: true,

          @if($name == "employee_documents" || $name == "profile_pic")
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
  })




  uppy_{{ $name }}.on('upload-success', (file, response) => {
      
      const elemn = document.querySelector("#{{ $name }}");
      
      console.log('Uppy upload success for {{ $name }}:', response.body.folder);
      elemn.value = response.body.folder;
      
  });

  uppy_{{ $name }}.on('complete', (result) => {
      console.log('Uppy upload complete for {{ $name }}:', result);
  });

  uppy_{{ $name }}.on('upload-error', (file, error, response) => {
      console.error('Uppy upload error for {{ $name }}:', error);
  });



</script>