<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'Times New Roman';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('/fonts/times.ttf') }}) format('truetype');
        }
        @font-face {
            font-family: 'Times New Roman';
            font-style: normal;
            font-weight: bold;
            src: url({{ asset('/fonts/timesbd.ttf') }}) format('truetype');
        }
        body {
            font-family: "Times New Roman", serif !important;
            font-size: 12px;
        }
        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 10px;
            font-family: "Times New Roman", serif !important;
            font-size: 12px;
        }
        h1 {
            font-family: "Times New Roman", serif !important;
            font-weight: bold;
            font-size: 12px;
            padding-top: 0px;
            padding-bottom: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .text-center {
            text-align: center;
        }
        /*p {*/
            /*text-indent: 40px;*/
            /*!*line-height: 7px;*!*/
        /*}*/
        .div-list.bs > div {
            margin-bottom: 10px;
        }
        .div-list.bs-0 > div {
            margin-bottom: 0px;
        }
        .div-list {
            position: relative;
        }
        .div-list > div {
            position: relative;
        }
        .div-list .d-inline {
            text-align: justify;
        }
        .div-list span {
            line-height: 10px;
        }
        .div-list div .d-inline:first-child {
            width: 40px;
            position: absolute;
            top: 0;
            left: 0;
            line-height: 1 !important;
        }
        .div-list .d-inline:last-child {
            width: 660px;
            margin-right: auto;
            margin-left: 40px;
        }
        .d-inline {
            display: inline-block; !important;
        }
        table.req-table {
            padding-left: 55px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            margin-top: 30px;
        }
        table.req-table td,
        table.req-table th {
            border: 1px solid black;
            vertical-align: text-top;
            padding-left: 8px;
            padding-top: 0px;
            padding-bottom: 15px;
            line-height: 0.9;
            font-size: 13px;
        }
    </style>
    <meta charset="UTF-8">
</head>

<body>
    <footer>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td style="border: 1px white; text-align: left;">
                    <span>Экспедитор _____________</span>
                    <img src="{{ asset('/images/pdf/signature.png') }}" style="position: absolute; top: 0px; left: 80px; width: 50px; height: auto;" alt="">
                </td>
                <td style="border: 1px white; text-align: right;">
                    Заказчик _____________
                </td>
            </tr>
            </tbody>
        </table>
    </footer>
    <h1 class="text-center" style="line-height: 1">ДОГОВОР ТРАНСПОРТНОЙ ЭКСПЕДИЦИИ №{{ $data['Номер'] ?? '' }}</h1>
    <h1 class="text-center" style="line-height: 1; margin-top: 10px;">автомобильным транспортом</h1>
    <div style="margin-top: 10px; margin-bottom: 60px;">
        <span style="font-size: 10px !important;">г. Санкт-Петербург</span>
        <span style="float: right; margin-right: -15px;"><strong>{{ $data['Дата'] ?? '' }}</strong></span>
    </div>
    @yield('top')
    @include('v1.pdf.contracts.general-content')
    @yield('bottom')
</body>

</html>