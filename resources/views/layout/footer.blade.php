<script type="text/javascript" src="{{ asset('/js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap-material-design.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/dropdown.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('/js/snackbar.min.js') }}"></script>--}}
@yield('extrajs')
<script type="text/javascript">var msgSnack = '{{ Session::get('message') }}';</script>
<script type="text/javascript" src="{{ asset('/js/global.js') }}"></script>
</body>
</html>