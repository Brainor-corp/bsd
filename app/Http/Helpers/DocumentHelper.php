<?php

namespace App\Http\Helpers;

use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use alhimik1986\PhpExcelTemplator\setters\CellSetterStringValue;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class DocumentHelper
{
    public static function generateContractDocument($documentName, Array $parameters)
    {
        $TBS = new \clsTinyButStrong();
        $TBS->Plugin(TBS_INSTALL, \clsOpenTBS::class);

        $type = '';
        switch ($parameters['ЮридическоеФизическоеЛицо']) {
            case 'Юридическое лицо':
                $type = 'ur';
                $parameters['ПостфиксПолаКонтрагента'] = 'его';
                if(isset($parameters['ПолРуководителяКонтрагента']) && $parameters['ПолРуководителяКонтрагента'] == 'Женский') {
                    $parameters['ПостфиксПолаКонтрагента'] = 'ей';
                }
                break;
            case 'Физическое лицо':
                $parameters['ПостфиксПолаКонтрагента'] = 'инина';
                if(isset($parameters['ПолРуководителяКонтрагента']) && $parameters['ПолРуководителяКонтрагента'] == 'Женский') {
                    $parameters['ПостфиксПолаКонтрагента'] = 'нки';
                }
                $type = 'fiz';
                break;
            default: break;
        }

        $TBS->LoadTemplate(public_path("templates/ContractTemplate-$type.docx"));

        $TBS->SetOption('charset', 'UTF-8');
        $TBS->SetOption('render', TBS_OUTPUT);

        foreach ($parameters as $name => $value) {
            $TBS->MergeField($name, $value);
        }

        $name = md5('docs bsd' . time()) . '.docx';
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;

        File::makeDirectory($path, $mode = 0777, true, true);

        $TBS->Show(OPENTBS_FILE, $tempFile);

        return [
            'tempFile' => $tempFile,
            'fileName' => $documentName . '.docx'
        ];
    }

    public static function generateReceiptDocument($documentName, Array $parameters = null)
    {
        $params = [];
        $keys = array_keys($parameters);
        foreach($keys as $key) {
            $newKey = "{" . $key . "}";
            $params[$newKey] = $parameters[$key];
        }

        $params["{СуммаСкидки}"] = new ExcelParam(CellSetterStringValue::class, floatval($parameters['СтоимостьПоТарифу']) - floatval($parameters['СтоимостьПеревозки']) + floatval($parameters['СуммаСложныйГруз']));

        $name = md5('docs bsd' . time()) . '.xlsx';
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile(public_path('templates/ReceiptTemplate.xlsx'), $tempFile, $params);

        return [
            'tempFile' => $tempFile,
            'fileName' => $documentName . '.xlsx'
        ];
    }

    public static function generateInvoiceDocument($documentData, $documentName)
    {
        foreach($documentData['Товары'] as $index => $item) {
            $items['number'][] = $index + 1;
            $items['name'][] = $item['Содержание'];
            $items['quantity'][] = $item['Количество'];
            $items['points'][] = 'шт.';
            $items['price'][] = $item['Цена'];
            $items['sum'][] = $item['Сумма'];
        }
        foreach($documentData as $key => $var) {
            $params["{{$key}}"] = new ExcelParam(CellSetterStringValue::class, $var);
        }
        $params["{ВсегоТоваров}"] = new ExcelParam(CellSetterStringValue::class, count($documentData['Товары']));
        $params['[service_number]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['number']);
        $params['[service_name]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['name']);
        $params['[service_quantity]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['quantity']);
        $params['[service_point]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['points']);
        $params['[service_price]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['price']);
        $params['[service_summary]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['sum']);

        $name = md5('docs bsd' . time()) . '.xlsx';
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile(public_path('templates/InvoiceTemplate.xlsx'), $tempFile, $params);

        return [
            'tempFile' => $tempFile,
            'fileName' => $documentName . '.xlsx'
        ];
    }

    public static function generateRequestDocument($documentData, $documentName)
    {
        $view = View::make('v1.pdf.document-request')->with(compact('documentData'));
        $html = $view->render();

        $pdf = new TCPDF();
        $pdf::SetTitle($documentName);
        $pdf::AddPage();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output(storage_path($documentName), 'F');

        return [
            'tempFile' => storage_path($documentName),
            'fileName' => $documentName
        ];
    }

    static function removeSpaces($string) {
        $string = htmlentities($string, null, 'utf-8');
        $string = str_replace("&nbsp;", "", $string);
        $string = html_entity_decode($string);

        return $string;
    }

    public static function generateTransferDocument($documentData, $documentName)
    {
        foreach($documentData as $key => $var) {
            $params["{{$key}}"] = new ExcelParam(CellSetterStringValue::class, $var);
        }

        $params['[СтоимостьБезНалога]'] = 0;
        $params['[СуммаНалога]'] = 0;
        $params['[СтоимостьРабот]'] = 0;

        $params['[Статус]'] = $params['{ДокументБезНДС}'] === 'Да' ? 2 : 1;

        $documentData['Услуги'] = array_map(function ($el) {
            $el['СуммаБезНдс'] = floatval($el['Сумма']) - floatval($el['СуммаНДС']);

            $el['КодТовара'] = '--';
            $el['КодВидаТовара'] = '--';
            $el['КодЕдиницыИзмерения'] = '--';
            $el['УсловноеОбозначениеЕдиницыИзмерения'] = '--';
            $el['СуммаАкциза'] = 'Без акциза';
            $el['ЦифровойКодСтраны'] = '--';
            $el['КраткоеНаименованиеСтраны'] = '--';
            $el['РегистрационныйНомер'] = '--';

            return $el;
        }, $documentData['Услуги']);

        foreach($documentData['Услуги'] as $datum) {
            $params['[СтоимостьБезНалога]'] += floatval(self::removeSpaces($datum['СуммаБезНдс']));
            $params['[СуммаНалога]'] += floatval(self::removeSpaces($datum['СуммаНДС']));
            $params['[СтоимостьРабот]'] += floatval(self::removeSpaces($datum['Сумма']));
        }

        $params['[Номер]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_map(function ($el) { return $el + 1; }, array_keys($documentData['Услуги'])));
        $params['[Содержание]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'Содержание'));
        $params['[Номенклатура]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'Номенклатура'));
        $params['[Количество]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'Количество'));
        $params['[Цена]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'Цена'));
        $params['[Сумма]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'Сумма'));
        $params['[СуммаБезНдс]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'СуммаБезНдс'));
        $params['[СтавкаНДС]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'СтавкаНДС'));
        $params['[СуммаНДС]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'СуммаНДС'));

        $params['[КодТовара]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'КодТовара'));
        $params['[КодВидаТовара]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'КодВидаТовара'));
        $params['[КодЕдиницыИзмерения]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'КодЕдиницыИзмерения'));
        $params['[УсловноеОбозначениеЕдиницыИзмерения]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'УсловноеОбозначениеЕдиницыИзмерения'));
        $params['[СуммаАкциза]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'СуммаАкциза'));
        $params['[ЦифровойКодСтраны]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'ЦифровойКодСтраны'));
        $params['[КраткоеНаименованиеСтраны]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'КраткоеНаименованиеСтраны'));
        $params['[РегистрационныйНомер]'] = new ExcelParam(CellSetterArrayValueSpecial::class, array_column($documentData['Услуги'], 'РегистрационныйНомер'));

        $name = md5('docs bsd' . time()) . '.xlsx';
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile(public_path('templates/TransferTemplate.xlsx'), $tempFile, $params);

        return [
            'tempFile' => $tempFile,
            'fileName' => $documentName . '.xlsx'
        ];
    }
}