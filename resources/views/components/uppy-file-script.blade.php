<script>



const uppy_{{ $name }} = new Uppy.Uppy({
    id: 'uppy_{{ $name }}',
    autoProceed: false,
    allowMultipleUploads: false,
    debug: false,
    retryUpload: true,
    restrictions: {
     
        
        @if($onlyPdf === 'true')
        maxFileSize: 3194304,
        maxNumberOfFiles: 1,
         allowedFileTypes: [".pdf"],
        @else
        maxFileSize: 4194304,
        maxNumberOfFiles: 1,
        allowedFileTypes: [ ".jpg", ".jpeg", ".png", ".gif"],
       
        @endif
     
    },

    
    infoTimeout: 5000
})
.use(Uppy.Dashboard, {
  trigger: '.UppyModalOpenerBtn',
  inline: true,
  target: '.UppyInput_{{ $name }}',
  replaceTargetContent: true,
  showProgressDetails: true,
 
  @if($onlyPdf === 'true')
  note: '1 pdf file only, up to 3 MB. If you have multiple files compile them into 1 pdf file and upload',
  height: 350,
  width: 800,
  
  @else
    note: '1 image file only, up to 4 MB',
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
})

// .use(GoogleDrive, { target: Dashboard, companionUrl: 'https://companion.uppy.io' })
 @if($onlyPdf !== 'true')

  .use(Uppy.Webcam, { target: Uppy.Dashboard })
  .use(Uppy.ImageEditor, { target: Uppy.Dashboard })
@endif
// .use(Tus, { endpoint: 'https://tusd.tusdemo.net/files/' })
.use(Uppy.DropTarget, {target: 'uppy_{{ $name }}' })
.use(Uppy.GoldenRetriever)
.use(Uppy.Compressor)
uppy_{{ $name }}.use(Uppy.XHRUpload, {
    endpoint: "{{ route('uploads') }}",
        
        formData: true,
    
        fieldName: '{{ $name }}',
        
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
    
    elemn.value = response.body.folder;

    
    
});



</script>