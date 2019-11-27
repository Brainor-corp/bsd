<?php

namespace App\Http\Controllers;

use App\Type;

class UploadCsvController extends Controller
{
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public function loadOrderTypes() {
        $data = $this->csvToArray(public_path('file.csv'));

        foreach($data as $datum) {
            $type = new Type();
            $type->name = $datum['name'];
            $type->option = $datum['id'];
            $type->class = 'cargo_type';
            $type->save();
        }

        return 'ok';
    }
}
