@extends('v1.layouts.contractPDFLayout')

@section('top')
    <p style="padding-left: 50px; text-align: justify; margin-bottom: 7px; text-indent: 35px;">
        <strong>[ОрганизацияНаименованиеПолное]</strong>, именуемое в дальнейшем «Экспедитор»,
        в лице [ДолжностьРуководителя] <strong>[Руководитель]</strong>, действующего на основании Устава,
        и гражданин, именуемый в дальнейшем «Заказчик», [КонтрагентНаименованиеПолное],
        паспорт №[ДокументУдостоверяющийЛичностьНомер] серия [ДокументУдостоверяющийЛичностьСерия],
        с другой стороны, а при совместном упоминании «Стороны», заключили настоящий договор о нижеследующем:
    </p>
@endsection

@section('bottom')
    <table style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="position: relative; width: 405px; vertical-align: top;">
                <img src="{{ asset('/images/pdf/stamp.png') }}" style="position: absolute; top: 0px; left: 20px; width: 200px; height: auto;" alt="">
                <span>[ДолжностьРуководителя] <br> <br></span>
                <strong>ООО «Балтийская Служба Доставки»</strong>
                <div style="margin-top: 40px;">
                    <div style="display: inline-block; width: 190px; border-bottom: 1px solid black;">
                        <img src="{{ asset('/images/pdf/signature.png') }}" style="padding-left:50px; width: 50px; height: auto;" alt="">
                    </div>
                    <div style="display: inline-block;">Бабкин А.С</div>
                </div>
            </td>
            <td style="vertical-align: top;">
                Заказчик <br> <br>
                <strong>[КонтрагентНаименованиеПолное]</strong>
                <div style="margin-top: 40px;">
                    <div style="display: inline-block; width: 100px; border-bottom: 1px solid black;"></div>
                    <div style="display: inline-block;">[КонтрагентНаименование]</div>
                </div>
            </td>
        </tr>
    </table>
@endsection