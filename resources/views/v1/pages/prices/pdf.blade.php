<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans";
            font-size: 7pt;
        }
        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        table td {
            border: 1px solid #777777;
        }
        table th {
            border: 1px solid #777777;
            font-weight: bold !important;
        }
        .border {
            border-bottom: 2px solid black;
        }
        h4 {
            font-size: 14px !important;
            font-weight: normal !important;
        }
        @page { margin: 80px 25px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; height: 50px; }
    </style>
</head>
<body>
    <header>
        <table style="width: 100%; font-family: 'DejaVu Sans'; font-size: 7pt">
            <tbody>
            <tr>
                <td style="border: 1px white; text-align: left;">
                    <img src="{{ asset('/images/img/pdflogo.jpg') }}" style="width: 100px; height: auto">
                </td>
                <td style="border: 1px white; text-align: right;">Прайс-лист на {{ \Carbon\Carbon::now()->format('d.m.Y') }}</td>
            </tr>
            </tbody>
        </table>
    </header>
    @include('v1.pages.prices.prices-content')
</body>
</html>