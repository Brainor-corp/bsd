<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans";
            font-size: 7pt;
        }
        .header-1 {
            text-align: center;
            font-size: 14pt!important;
        }
        .header-2 {
            text-align: center;
            font-size: 12pt!important;
            margin-bottom: 0;
            margin-top: 0;
        }
        .table-block {
            padding-left: 5px;
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
        .mb-0 {
            margin-bottom: 0;
        }
        hr {
            border-width: 0 1px 1px 1px;
            margin: 0;
            border-color: #777777;
        }
        .hl {
            color: red;
        }
    </style>
</head>
<body>
<div>
    <p class="header-1"><b>{{ $documentData['Представление'] ?? '' }}</b></p>
    <span>Дата исполнения (дата подачи авто): <i><b><u>{{ $documentData['ДатаИсполнения'] ?? '' }}</u></b></i></span>
    <p class="header-2"><b>Информация о грузе</b></p>
    <span>Характер груза:  <i><b><u>{{ $documentData['Груз'] ?? '' }}</u></b></i></span>
    <br>
    <span>Вес: <i><b><u>{{ $documentData['Вес'] ?? '' }} кг</u></b></i> Обьем: <i><b><u>{{ $documentData['Объем'] ?? '' }} м<sup><small>3</small></sup></u></b></i> Количество мест: <i><b><u>{{ $documentData['КоличествоМест'] ?? '' }} шт</u></b></i></span>
    @if(isset($documentData['Места']) && count($documentData['Места']))
        <div class="table-block">
            <table>
                <thead>
                <tr>
                    <td>Длина, м</td>
                    <td>Ширина, м</td>
                    <td>Высота, м</td>
                    <td>Объем, м3</td>
                    <td>Вес, кг</td>
                    <td>Количество</td>
                </tr>
                </thead>
                <tbody>
                @foreach($documentData['Места'] as $package)
                    <tr>
                        <td>{{ $package['Длина'] ?? '' }}</td>
                        <td>{{ $package['Ширина'] ?? '' }}</td>
                        <td>{{ $package['Высота'] ?? '' }}</td>
                        <td>{{ $package['Объем'] ?? '' }}</td>
                        <td>{{ $package['Вес'] ?? '' }}</td>
                        <td>{{ $package['Количество'] ?? '' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <span style="">Примечания по грузу:</span><br><br>
    <span>Мягкая упакова: <i><b><u>{{ $documentData['МягкаяУпаковка'] ?? '' }}</u></b></i></span><br>
    <span>Жёсткая упакова: <i><b><u>{{ $documentData['ЖесткаяУпаковка'] ?? '' }}</u></b></i></span><br>
    <span>Паллетирование: <i><b><u>{{ $documentData['Паллетирование'] ?? '' }}</u></b></i></span><br>

    @php
        $sum = 0;
        if(!empty($documentData['Сумма_страховки'])) {
            $sum = floatval(str_replace(' ', '', $documentData['Сумма_страховки']));
        }
    @endphp

    @if($sum)
        <span>Страхование: <i><b><u>Да, на сумму {{ $documentData['Сумма_страховки'] ?? '' }}</u></b></i></span><br>
    @else
        <span>Страхование: <i><b><u>Нет</u></b></i></span><br>
    @endif


    <p class="header-2"><b>Отправитель</b></p>
    <span>Направление (город) отправления: <i><b><u>{{ $documentData['ГородОтправления'] ?? '' }}</u></b></i></span><br>
    <span>Название компании : <i><b><u>{{ $documentData['ОрганизацияОтправления'] ?? '' }}</u></b></i></span><br>
    <span>Телефон (контактное лицо): <i><b><u>{{ $documentData['ГрузоотправительТелефон'] ?? '' }}</u></b></i></span><br>
    <span>Адрес забора груза: <i><b><u>{{ $documentData['АдресЗабора'] ?? '' }}</u></b></i></span><br>
    <span>Режим работы склада: <i><b><u>{{ $documentData['РежимРаботыСклада'] ?? '' }}</u></b></i></span><br>
    <p class="header-2"><b>Получатель</b></p>
    <span>Направление (город) доставки: <i><b><u>{{ $documentData['ГородНазначения'] ?? '' }}</u></b></i></span><br>
    <span>Название компании получателя или частное лицо (Ф.И.О.): <i><b><u>{{ $documentData['Грузополучатель'] ?? '' }}</u></b></i></span><br>
    <span>Телефон получателя: <i><b><u>{{ $documentData['ГрузополучательТелефон'] ?? '' }}</u></b></i></span><br>
    <span>Полный адрес (в случае необходимости доставки груза "до двери" получателя): <i><b><u>{{ $documentData['АдресДоставки'] ?? '' }}</u></b></i></span><br>
    <span>Контактное лицо: <i><b><u>{{ $documentData['КонтактноеЛицоГрузополучателя'] ?? '' }}</u></b></i></span><br>
    <span>Нужна ли доставка до адреса получателя: <i><b><u>{{ !empty($documentData['АдресГрузополучателя']) ? 'Да' : '-' }}</u></b></i></span><br>
    <p class="header-2"><b>Плательщик</b></p>
    <span>Наименование пательщика: <i><b><u>{{ $documentData['Плательщик'] ?? '' }}</u></b></i></span><br>
    <span>Форма оплаты: <i><b><u>{{ $documentData['ФормаОплаты'] ?? '' }}</u></b></i></span><br>
    <span>Реквизиты: <i><b><u></u></b></i></span><br>
    <span>Заявку заполнил: <i><b><u>{{ $documentData['Заявку_заполнил'] ?? '' }}</u></b></i></span>
    <span>E-mail: <i><b><u>{{ $documentData['ПлательщикEmail'] ?? '' }}</u></b></i></span><br>
    <p class="header-2 mb-0"><b>ВНИМАНИЕ!</b></p>
    <hr>
    <span>ЗАЯВКИ ПРИНИМАЮТСЯ с 9.00 до 16.00 за сутки до исполнения.</span><br>
    <span>Заявки, поступившие после 16.00 выполняются по мере возможности.</span><br>
    <span>ВОДИТЕЛЬ-ЭКСПЕДИТОР НЕ ЗАНИМАЕТСЯ ПОГРУЗОЧНО-РАЗГРУЗОЧНЫМИ РАБОТАМИ!!</span><br>
    <ul style="padding-left: 10px !important;">
        <li>
            доставка осуществляется только до адреса. Погрузка-выгрузка и поднятие на этаж - возможны по предварительному согласованию с менеджером направления.
        </li>
        <li>
            исполнение заявки в течении рабочего дня (с 10.00 до 18.00) , указание конкретного времени подачи машины осложняет выполнение заявки, стоимость может увеличиться до 200%.
        </li>
        <li>
            погрузка-выгрузка а/м 30 минут, простой каждого последующего часа оплачивается по прайс-листу.
        </li>
        <li>
            неправильно указанные координаты (адрес, телефон), прогон а/м, отказ от заявки по факту подачи а/м - считается выполненной заявкой и оплачивается по прайс-листу.
        </li>
        <li>
            оплата въезда на территорию клиента – оплачивается дополнительно.
        </li>
        <li>
            областная доставка расчитывается по формуле: автомобильная доставка по СПб + пробег * руб./км в оба конца, согласно прайс-листа.
        </li>
    </ul>
    <span>На указанный Вами электронный адрес в течение часа поступит информация о принятии данной заявки к исполнению.</span><br>
</div>
</body>
</html>