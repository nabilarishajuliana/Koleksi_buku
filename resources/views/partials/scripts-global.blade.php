{{-- ================= JS GLOBAL ================= --}}
<script src="{{ asset('template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('template/assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('template/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('template/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('template/assets/js/misc.js') }}"></script>
<script src="{{ asset('template/assets/js/settings.js') }}"></script>
<script src="{{ asset('template/assets/js/todolist.js') }}"></script>
<script src="{{ asset('template/assets/js/jquery.cookie.js') }}"></script>

{{-- ================= JS PAGE ================= --}}
@yield('script')
