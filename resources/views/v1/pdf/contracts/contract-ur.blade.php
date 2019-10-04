@extends('v1.layouts.contractPDFLayout')

@section('top')
    <p style="padding-left: 50px; text-align: justify; margin-bottom: 7px; text-indent: 35px;">
        [ОрганизацияНаименованиеПолное], именуемое в дальнейшем «Экспедитор», в лице [ДолжностьРуководителя]
        [Руководитель], действующего на основании Устава, и [КонтрагентНаименованиеПолное], именуемое в дальнейшем
        «Заказчик», в лице [ДолжностьРуководителяКонтрагента] [РуководительКонтрагента],
        действующе[ПостфиксПолаКонтрагента] на основании Устава, с другой стороны, а при совместном упоминании «Стороны»,
        заключили настоящий договор о нижеследующем:
    </p>
@endsection

@section('bottom')
    <table style="width: 100%; margin-top: 50px;">
        <tr>
            <td style="width: 405px; vertical-align: top;">
                [ДолжностьРуководителя] <br> <br>
                <strong>ООО «Балтийская Служба Доставки»</strong>
                <div style="margin-top: 40px;">
                    <div style="display: inline-block; width: 190px; border-bottom: 1px solid black;"></div>
                    <div style="display: inline-block;">Бабкин А.С</div>
                </div>
            </td>
            <td style="vertical-align: top;">
                [ДолжностьРуководителяКонтрагента] <br> <br>
                <strong>[КонтрагентНаименованиеПолное]</strong>
                <div style="margin-top: 40px;">
                    <div style="display: inline-block; width: 100px; border-bottom: 1px solid black;"></div>
                    <div style="display: inline-block;">[РуководительКонтрагента]</div>
                </div>
            </td>
        </tr>
    </table>
@endsection