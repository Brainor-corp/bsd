<!doctype html>
<html lang="ru">
<head>
    <style>
        body {
            font-family: "DejaVu Sans";
            font-size: 9px;
        }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; color: #a2a2a2 }
        h1 {
            font-weight: bold;
            font-size: 9px;
            padding-top: 0px;
            padding-bottom: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .text-center {
            text-align: center;
        }
        p {
            text-indent: 100px;
        }
    </style>
    <meta charset="UTF-8">
</head>

<body>
    <footer>
        <table style="width: 100%; font-family: 'DejaVu Sans'; font-size: 7pt">
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
    <h1 class="text-center">ДОГОВОР ТРАНСПОРТНОЙ ЭКСПЕДИЦИИ №[Номер]</h1>
    <h1 class="text-center">автомобильным транспортом</h1>
    <div>
        <span>г. Санкт-Петербург</span>
        <span style="float: right; font-size: 8px !important;">[Дата]</span>
    </div>
    @yield('top')
    @include('v1.pdf.contracts.general-content')
    @yield('bottom')
</body>

</html>