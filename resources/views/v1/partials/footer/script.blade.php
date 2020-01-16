
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/899e2bf82b.js"></script>
<script src="{{ asset('v1/js/jquery.mask.js') }}"></script>
<script src="{{ asset('packages/selectize/selectize.min.js') }}@include('v1.partials.versions.jsVersion')"></script>
<script src="{{ asset('v1/js/general.js') }}@include('v1.partials.versions.jsVersion')"></script>
<script src="{{ asset('v1/js/tooltip.js') }}@include('v1.partials.versions.jsVersion')"></script>
<script src="{{ asset('v1/js/lightbox.js') }}@include('v1.partials.versions.jsVersion')"></script>
<script src="//cdn.callibri.ru/callibri.js" type="text/javascript" charset="utf-8"></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_CAPTCHA_KEY') }}"></script>

@yield('footerScripts')
