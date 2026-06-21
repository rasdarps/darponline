      <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>

        <!-- apexcharts -->
        <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>
        
        {{-- Yajra Data Table Scripts --}}
         <!-- CDN Scripts -->
         <!-- JQUERY DataTables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('uppy/dist/uppy.min.js') }}"></script>

        <!-- Responsive -->
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
        <!-- Buttons -->
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <!-- Export dependencies -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <!-- Buttons features -->
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        
        <!-- Datatable init js -->
        <script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script>
        <script src="{{ asset('backend/datatables/buttons.server-side.js') }}"></script>

        {{-- Yajra Data Table Scripts end--}}

        <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>

        <script src="{{ asset('backend/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/js/pages/form-advanced.init.js') }}"></script>

        <script src="{{ asset('notiflix/notiflix-3.2.8.min.js') }}"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}";
 var message = "{{ Session::get('message') }}";
 if (window.Notiflix && Notiflix.Notify) {
     Notiflix.Notify.init({
         position: 'right-top',
         timeout: 3000,
         showOnlyTheLastOne: true
     });

     switch(type){
         case 'info':
         Notiflix.Notify.info(message);
         break;

         case 'success':
         Notiflix.Notify.success(message);
         break;

         case 'warning':
         Notiflix.Notify.warning(message);
         break;

         case 'error':
         Notiflix.Notify.failure(message);
         break;

         default:
         Notiflix.Notify.success(message);
         break;
     }
 }
 @endif 
 
</script>

 <!-- field Validation -->
 <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <script src="{{ asset('backend/assets/js/code.js') }}"></script>
 <script src="{{ asset('backend/assets/js/handlebars.js') }}"></script>
 <script src="{{ asset('backend/assets/js/notify.min.js') }}"></script>

 <!--Only Text and Numbers Script-->
<script>
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    // Allow only alphabetic characters, space, dot, and hyphen
    function isCharKey(evt){
        var keyunicode = event.charCode || event.keyCode;
        // Check for alphabetic characters, backspace, space, dot, and hyphen
        return (
            (keyunicode >= 65 && keyunicode <= 90) ||  // A-Z
            (keyunicode >= 97 && keyunicode <= 122) || // a-z
            keyunicode == 32 ||   // Space
            keyunicode == 46 ||   // Dot (.)
            keyunicode == 45      // Hyphen (-)
        );
    }
</script>

<script>
    //calling datatable function 
    $(document).ready( function () {
    $('#myTable').DataTable(
        {
            paging: true,
           lengthChange: true,
           searching: true,
           ordering: true,
           info: true,
           autoWidth: false,
            dom: 'lBfrtip',

           responsive: true,
           buttons: [ "csv","print",
           {
            extend: 'copy',
            exportOptions: {
                columns:  [ 0, 1, 2, 3, 4, 5, 6, 7]
            }
        },
        {
            extend: 'excel',
            exportOptions: {
                columns:  [ 0, 1, 2, 3, 4, 5, 6, 7]
            }
        },
        {
            extend: 'pdf',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
            }
        },


        'colvis' ],
         })
         .buttons()
          .container()
          .appendTo("#example_wrapper .col-md-6:eq(0)");
          

    });
</script>

<script>
    //calling datatable function 
    $(document).ready( function () {
    $('#NewTable').DataTable(
        {
            
           
         })
         .buttons()
          .container()
          .appendTo("#example_wrapper .col-md-6:eq(0)");
          

    });
</script>

{{-- //Download JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    async function downloadPDF() {
        const { jsPDF } = window.jspdf;

        // Capture invoice area
        const invoice = document.getElementById("invoiceArea");
        const canvas = await html2canvas(invoice, { scale: 2 }); // higher scale = better quality
        const imgData = canvas.toDataURL("image/png");

        // Create PDF
        const pdf = new jsPDF("p", "mm", "a4");
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        // Calculate image dimensions to fit page
        const imgWidth = pageWidth;
        const imgHeight = canvas.height * imgWidth / canvas.width;

        let position = 0;
        pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);

        // Save file
        pdf.save("invoice.pdf");
    }
</script>

<script type="text/javascript">
// Disable right-click
document.addEventListener('contextmenu', e => e.preventDefault());

// Disable common DevTools shortcuts
document.addEventListener('keydown', e => {
    if (e.ctrlKey && (
        e.key.toLowerCase() === 'u' || // View source
        e.key.toLowerCase() === 's' || // Save
        e.key.toLowerCase() === 'i' || // DevTools
        e.key.toLowerCase() === 'j'    // Console
    )) {
        e.preventDefault();
    }
});
</script>
