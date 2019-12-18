<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_GEO_KEY') }}&lang=ru_RU" type="text/javascript">
</script>
<script src="{{ asset('/packages/kladrapi-js/jquery.kladr.min.js') }}"></script>

@yield('headerScripts')
