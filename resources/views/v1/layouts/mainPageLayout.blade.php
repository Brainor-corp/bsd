<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>

    @include('v1.partials.header.meta')

    @include('v1.partials.header.style')

    @include('v1.partials.header.script')

</head>

    <body>

        @include('v1.partials.header.mainPageHeader')

        @yield('content')

        @include('v1.partials.footer.footer')

    </body>

    @include('v1.partials.footer.script')

    @include('v1.partials.footer.modalSelectionCity')

</html>