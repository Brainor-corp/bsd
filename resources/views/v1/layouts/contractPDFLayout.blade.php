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
        p {
            text-indent: 50px;
            line-height: 7px;
        }
        .table-list.bs {
            border-spacing: 0px 10px;
        }
        .table-list.bs-0 {
            border-spacing: 0px 0px;
        }
        .table-list th,
        .table-list td {
            vertical-align: text-top;
            text-align: justify;
            padding-top: 0px;
            padding-bottom: 0px;
        }
        .table-list td span {
            line-height: 10px;
        }
        .table-list th {
            font-weight: normal;
            width: 30px;
            line-height: 0.9 !important;
        }
        table.req-table {
            padding-left: 35px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        table.req-table td,
        table.req-table th {
            border: 1px solid #777777;
            vertical-align: text-top;
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
                    Экспедитор _____________
                </td>
                <td style="border: 1px white; text-align: right;">
                    Заказчик _____________
                </td>
            </tr>
            </tbody>
        </table>
    </footer>
    <h1 class="text-center" style="line-height: 1">ДОГОВОР ТРАНСПОРТНОЙ ЭКСПЕДИЦИИ №[Номер]</h1>
    <h1 class="text-center" style="line-height: 1">автомобильным транспортом</h1>
    <div style="margin-bottom: 36px">
        <span style="font-size: 10px !important;">г. Санкт-Петербург</span>
        <span style="float: right; margin-right: -15px;"><strong>[Дата]</strong></span>
    </div>
    @yield('top')
    @include('v1.pdf.contracts.general-content')
    @yield('bottom')
</body>

</html>