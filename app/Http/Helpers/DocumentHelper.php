<?php

namespace App\Http\Helpers;

use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use alhimik1986\PhpExcelTemplator\setters\CellSetterStringValue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Elibyy\TCPDF\Facades\TCPDF;

class DocumentHelper
{
    public static function generateContractDocument($documentName, Array $parameters)
    {
        $TBS = new \clsTinyButStrong();
        $TBS->Plugin(TBS_INSTALL, \clsOpenTBS::class);

        $TBS->LoadTemplate(public_path('templates/ContractTemplate.docx'));

        $TBS->SetOption('charset', 'UTF-8');
        $TBS->SetOption('render', TBS_OUTPUT);

        array_push($parameters, ['ПостфиксПолаКонтрагента' => $parameters['ПолРуководителяКонтрагента'] == 'Мужской' ? 'го' : 'ей']);

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
        $params["{СуммаТоваров}"] = new ExcelParam(CellSetterStringValue::class, array_sum(array_column($documentData['Товары'], 'Сумма')));
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

    public static function generateTransferDocument($documentData, $documentName)
    {
//        foreach($documentData['Товары'] as $index => $item) {
//            $items['number'][] = $index + 1;
//            $items['name'][] = $item['Содержание'];
//            $items['quantity'][] = $item['Количество'];
//            $items['points'][] = 'шт.';
//            $items['price'][] = $item['Цена'];
//            $items['sum'][] = $item['Сумма'];
//        }
        foreach($documentData as $key => $var) {
            $params["{{$key}}"] = new ExcelParam(CellSetterStringValue::class, $var);
        }
//        $params['[service_number]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['number']);
//        $params['[service_name]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['name']);
//        $params['[service_quantity]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['quantity']);
//        $params['[service_point]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['points']);
//        $params['[service_price]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['price']);
//        $params['[service_summary]'] = new ExcelParam(CellSetterArrayValueSpecial::class, $items['sum']);

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