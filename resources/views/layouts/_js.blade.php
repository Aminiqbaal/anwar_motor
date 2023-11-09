{{-- JQuery --}}
<script src="{{ url('vendors/jquery/jquery.min.js') }}"></script>
{{-- CoreUI and necessary plugins --}}
<script src="{{ url('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
<!--[if IE]><!-->
<script src="{{ url('vendors/@coreui/icons/js/svgxuse.min.js') }}"></script>
<!--<![endif]-->
{{-- InputMask --}}
<script src="{{ url('vendors/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('vendors/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
<!-- CanvaJS -->
<script src="{{ asset('vendors/canvasjs/jquery.canvasjs.min.js') }}"></script>
<!-- DateRange Picker -->
<script src="{{ asset('vendors/moment/moment.min.js') }}"></script>
<script src="{{ asset('vendors/daterangepicker/daterangepicker.js') }}"></script>
{{-- Bootstrap Date Picker --}}
<script src="{{ asset('vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendors/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-left',
        showConfirmButton: false,
        showCloseButton: true,
        timer: 5000,
        timerProgressBar: true,
    })

    @if(Session::has('toast'))
    Toast.fire({
        icon: "{{ Session::get('toast')[0] }}",
        title: "{{ Session::get('toast')[1] }}"
    })
    @endif

    @auth
    @if(Auth::user()->role != 'cashier')
    function get_notifications() {
        $.get('/get_notification', function(data) {
            $('#notification_content').html(data['content'])
            $('#notification_count').html(data['count'])
        })
    }

    function read_all() {
        $.ajax({
            method: 'GET',
            url: '/notification/read_all'
        })
        .done(function() {
            get_notifications()
        })
    }

    $(document).ready(function() {
        get_notifications()
    })
    @endif
    @endauth
</script>
