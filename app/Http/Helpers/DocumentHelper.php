<?php

namespace App\Http\Helpers;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
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

    public static function generatePETDocument($templatePath, $documentName, $documentExtension, Array $parameters = null, Array $blocks = null) {
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

    public static function generateInvoiceDocument($params)
    {
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

//    public static function generateForwardingReceipt($documentData) {
//        $templateFile = public_path('templates/ReceiptTemplate.xlsx');
//        $documentName = "Экспедиторская расписка №" . $documentData['УникальныйИдентификатор'];
//        $documentExtension = '.xlsx';
//
//        $tempFile = self::generatePETDocument($templateFile, $documentExtension, $documentData);
//
//        return [
//            'tempFile' => $tempFile,
//            'fileName' => $documentName . $documentExtension
//        ];
//    }

//    public static function generateInvoiceForPayment($documentData) {
//        $templateFile = public_path('templates/InvoiceTemplate.xlsx');
//        $documentName = "Счет на оплату № todo от todo";
//        $documentExtension = '.xlsx';
//
//        $tempFile = self::generatePETDocument($templateFile, $documentExtension, $documentData);
//
//        return [
//            'tempFile' => $tempFile,
//            'fileName' => $documentName . $documentExtension
//        ];
//    }
}