<?php

namespace App\Http\Helpers;

use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;
use alhimik1986\PhpExcelTemplator\setters\CellSetterStringValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DocumentHelper {
    public static function generateTBSDocument($templatePath, $documentExtension, Array $parameters = null, Array $blocks = null)
    {
        $TBS = new \clsTinyButStrong();
        $TBS->Plugin(TBS_INSTALL, \clsOpenTBS::class);

        $TBS->LoadTemplate($templatePath);

        $TBS->SetOption('charset', 'UTF-8');
        $TBS->SetOption('render', TBS_OUTPUT);

        foreach ($parameters as $name => $value) {
            $TBS->MergeField($name, $value);
        }

        if(isset($blocks)) {
            foreach ($blocks as $blockName => $blockValues) {
                $TBS->MergeBlock($blockName, $blockValues);
            }
        }

        $name = md5('docs bsd' . time()) . $documentExtension;
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;

        File::makeDirectory($path, $mode = 0777, true, true);

        $TBS->Show(OPENTBS_FILE, $tempFile);
        return $tempFile;
    }

    public static function generateReceiptDocument($templatePath, $documentName, $documentExtension, Array $parameters = null, Array $blocks = null) {
        $params = [];
        $keys = array_keys($parameters);
        foreach($keys as $key) {
            $newKey = "{" . $key . "}";
            $params[$newKey] = $parameters[$key];
        }

        $name = md5('docs bsd' . time()) . $documentExtension;
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile($templatePath, $tempFile, $params);

        return [
            'tempFile' => $tempFile,
            'fileName' => $documentName . $documentExtension
        ];
    }

    public static function generateInvoiceDocument($documentData)
    {
//        $items = [
//            'number' => [],
//            'name' => [],
//            'quantity' => [],
//            'points' => [],
//            'price' => [],
//            'sum' => [],
//        ];
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

        $date = Carbon::now();

        $tempFile = $path . $name;
        File::makeDirectory($path, $mode = 0777, true, true);

        PhpExcelTemplator::saveToFile(public_path('templates/InvoiceTemplate.xlsx'), $tempFile, $params);

        return [
            'tempFile' => $tempFile,
            'fileName' => "Счет на оплату № todo от $date" . '.xlsx'
        ];
    }

    public static function generateRequestDocument($documentData, $documentName)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('v1.pdf.document-request', $documentData);

        return $pdf->download($documentName);
    }
}