<?php

namespace App\Http\Controllers;

use App\RouteTariff;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadXmlController  extends Controller
{
    public function uploadCities(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];
                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                            switch ($column->attributes()->name) {
                                case 'id':
                                    $id = (string)$column;
                                    $rowData[$id]['id'] = (string)$column;
                                    break;
                                case 'kladr_id':
                                    if((string)$column == 'NULL'){
                                        $rowData[$id]['kladr_id'] = null;
                                    }else{
                                        $rowData[$id]['kladr_id'] = (string)$column;
                                    }
                                    break;
                                case 'name':
                                    $rowData[$id]['name'] = (string)$column;
                                    break;
                                case 'is_ship':
                                    $rowData[$id]['is_ship'] = (string)$column;
                                    break;
                                case 'is_filial':
                                    $rowData[$id]['is_filial'] = (string)$column;
                                    break;
                                case 'address':
                                    $rowData[$id]['address'] = (string)$column;
                                    break;
                                case 'phone':
                                    $rowData[$id]['phone'] = (string)$column;
                                    break;
                                case 'message':
                                    switch ((string)$column) {
                                        case '0': $typeId = 1; break;
                                        case '1': $typeId = 2; break;
                                        case '2': $typeId = 3; break;
                                        case '3': $typeId = 4; break;
                                        case '4': $typeId = 5; break;
                                    }
                                    $rowData[$id]['message'] = $typeId;
                                    break;
                                case 'doorstep':
                                    $rowData[$id]['doorstep'] = (string)$column;
                                    break;
                                case 'note':
                                    $rowData[$id]['note'] = (string)$column;
                                    break;
                                case 'tariff_zone':
                                    switch ((string)$column) {
                                        case '1': $typeId = 15; break;
                                        case '2': $typeId = 16; break;
                                        case '3': $typeId = 17; break;
                                        case '4': $typeId = 18; break;
                                        case '5': $typeId = 19; break;
                                        case '6': $typeId = 20; break;
                                        case '7': $typeId = 21; break;
                                        case '8': $typeId = 22; break;
                                        case '9': $typeId = 23; break;
                                        case '10': $typeId = 24; break;
                                        case '11': $typeId = 25; break;
                                        case '12': $typeId = 26; break;
                                        case '13': $typeId = 27; break;
                                        case '14': $typeId = 28; break;
                                        case '15': $typeId = 29; break;
                                    }
                                    $rowData[$id]['tariff_zone'] = $typeId;
                                    break;
                                case 'threshold_group':
                                    switch ((string)$column) {
                                        case '1': $typeId = 6; break;
                                        case '2': $typeId = 7; break;
                                        case '3': $typeId = 8; break;
                                        case '4': $typeId = 9; break;
                                        case '5': $typeId = 10; break;
                                        case '6': $typeId = 11; break;
                                        case '7': $typeId = 12; break;
                                        case '8': $typeId = 13; break;
                                        case '13': $typeId = 14; break;
                                    }
                                    $rowData[$id]['threshold_group'] = $typeId;
                                    break;
                                case 'coordinates':
                                    $rowData[$id]['coordinates'] = (string)$column;
                                    break;
                            }
                    }

                }
                foreach ($rowData as $row){
                        DB::table('cities')->insert([
                            'old_id' => $row['id'],
                            'kladr_id' => $row['kladr_id'],
                            'name' => $row['name'],
                            'slug' => str_slug($row['name']),
                            'is_ship' => $row['is_ship'],
                            'is_filial' => $row['is_filial'],
                            'message' => $row['message'],
                            'doorstep' => $row['doorstep'],
                            'tariff_zone_id' => $row['tariff_zone'],
                            'threshold_group_id' => $row['threshold_group'],
                            'created_at' => $nowTimestamp,
                            'updated_at' => $nowTimestamp,
                        ]);
                }
            }
        }
    }

    public function uploadThresholds(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'rate_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 26; break;
                                    case '2': $typeId = 27; break;
                                    case '3': $typeId = 28; break;
                                }
                                $rowData[$id]['rate_id'] = $typeId;
                                break;
                            case 'value':
                                $rowData[$id]['value'] = (string)$column;
                                break;
                        }
                    }

                }
                foreach ($rowData as $row){
                    DB::table('thresholds')->insert([
                        'old_id' => $row['id'],
                        'rate_id' => $row['rate_id'],
                        'value' => $row['value'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadRoutes(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'name':
                                $rowData[$id]['name'] = (string)$column;
                                break;
                            case 'ship_city_id':
                                $currentId = (string)$column;
                                $cityID = DB::table('cities')->where('old_id',$currentId)->first();
                                $rowData[$id]['ship_city_id'] = $cityID->id;
                                break;
                            case 'dest_city_id':
                                $currentId = (string)$column;
                                $cityID = DB::table('cities')->where('old_id',$currentId)->first();
                                $rowData[$id]['dest_city_id'] = $cityID->id;
                                break;
                            case 'min_cost':
                                $rowData[$id]['min_cost'] = (string)$column;
                                break;
                            case 'delivery_time':
                                if((string)$column == 'NULL' OR (string)$column == ''){
                                    $rowData[$id]['delivery_time'] = 0;
                                }else{
                                    $rowData[$id]['delivery_time'] = (string)$column;
                                }
                                break;
                            case 'base_route':
                                if((string)$column == 'NULL'){
                                    $rowData[$id]['base_route'] = null;
                                }else{
                                    $currentId = (string)$column;
                                    $routeID = DB::table('routes')->where('old_id',$currentId)->first();
                                    if($routeID){
                                        $rowData[$id]['base_route'] = $routeID->id;
                                    }else{
                                        $rowData[$id]['base_route'] = 'not found';
                                    }
                                }
                                break;
                            case 'addition':
                                if((string)$column == 'NULL'){
                                    $rowData[$id]['addition'] = 0;
                                }else{
                                    $rowData[$id]['addition'] = (string)$column;
                                }
                                break;
                            case 'oversizes_id':
                                if((string)$column == 'NULL' OR (string)$column == '0'){
                                    $rowData[$id]['oversizes_id'] = 1;
                                }else{
                                    $rowData[$id]['oversizes_id'] = (string)$column;
                                }
                                break;
                            case 'wrapper_tariff':
                                if((string)$column == 'NULL'){
                                    $rowData[$id]['wrapper_tariff'] = 0;
                                }else{
                                    $rowData[$id]['wrapper_tariff'] = (string)$column;
                                }
                                break;
                            case 'fixed_tariffs':
                                if((string)$column == 'NULL'){
                                    $rowData[$id]['fixed_tariffs'] = 0;
                                }else{
                                    $rowData[$id]['fixed_tariffs'] = (string)$column;
                                }
                                break;
                            case 'coefficient':
                                if((string)$column == 'NULL'){
                                    $rowData[$id]['coefficient'] = 0;
                                }else{
                                    $rowData[$id]['coefficient'] = (string)$column;
                                }
                                break;
                        }
                    }
                    if($rowData[$id]['base_route'] !== null){
                        DB::table('routes')->insert([
                            'old_id' => $rowData[$id]['id'],
                            'name' => $rowData[$id]['name'],
                            'ship_city_id' => $rowData[$id]['ship_city_id'],
                            'dest_city_id' => $rowData[$id]['dest_city_id'],
                            'min_cost' => $rowData[$id]['min_cost'],
                            'delivery_time' => $rowData[$id]['delivery_time'],
                            'base_route' => $rowData[$id]['base_route'],
                            'addition' => $rowData[$id]['addition'],
                            'oversizes_id' => $rowData[$id]['oversizes_id'],
                            'wrapper_tariff' => $rowData[$id]['wrapper_tariff'],
                            'fixed_tariffs' => $rowData[$id]['fixed_tariffs'],
                            'coefficient' => $rowData[$id]['coefficient'],
                            'created_at' => $nowTimestamp,
                            'updated_at' => $nowTimestamp,
                        ]);
                    }
                }
            }
        }
    }

    public function uploadRouteTariffs(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'route_id':
                                $currentId = (string)$column;
                                $routeID = DB::table('routes')->where('old_id',$currentId)->first();
                                $rowData[$id]['route_id'] = $routeID->id;
                                break;
                            case 'rate_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 26; break;
                                    case '2': $typeId = 27; break;
                                    case '3': $typeId = 28; break;
                                }
                                $rowData[$id]['rate_id'] = $typeId;
                                break;
                            case 'threshold':
                                $currentId = (string)$column;
                                $thresholdID = DB::table('thresholds')->where('old_id',$currentId)->first();
                                $rowData[$id]['threshold'] = $thresholdID->id;
                                break;
                            case 'tariff':
                                $rowData[$id]['tariff'] = (string)$column;
                                break;
                        }
                    }
                    DB::table('route_tariffs')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'route_id' => $rowData[$id]['route_id'],
                        'rate_id' => $rowData[$id]['rate_id'],
                        'threshold_id' => $rowData[$id]['threshold'],
                        'price' => $rowData[$id]['tariff'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    // У большинства тарифных маршрутов не указана мера,
    // зато мера указана у их Threshold'ов.
    public function updateRouteTariffsRates() {
        $routeTariffs = RouteTariff::with('threshold.rate')->get();
        foreach($routeTariffs as $routeTariff) {
            if($routeTariff->rate_id !== $routeTariff->threshold->rate->id) {
                DB::table('route_tariffs')
                    ->where('id', $routeTariff->id)
                    ->update([
                        'rate_id' => $routeTariff->threshold->rate->id
                    ]);
            }
        }
    }

    public function uploadForwardThresholds(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'weight':
                                $rowData[$id]['weight'] = (string)$column;
                                break;
                            case 'volume':
                                $rowData[$id]['volume'] = (string)$column;
                                break;
                            case 'units':
                                $rowData[$id]['units'] = (string)$column;
                                break;
                            case 'threshold_group_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 6; break;
                                    case '2': $typeId = 7; break;
                                    case '3': $typeId = 8; break;
                                    case '4': $typeId = 9; break;
                                    case '5': $typeId = 10; break;
                                    case '6': $typeId = 11; break;
                                    case '7': $typeId = 12; break;
                                    case '8': $typeId = 13; break;
                                    case '13': $typeId = 14; break;
                                }
                                $rowData[$id]['threshold_group_id'] = $typeId;
                                break;
                            case 'name':
                                $rowData[$id]['name'] = (string)$column;
                                break;
                        }
                    }
                    DB::table('forward_thresholds')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'weight' => $rowData[$id]['weight'],
                        'volume' => $rowData[$id]['volume'],
                        'units' => $rowData[$id]['units'],
                        'threshold_group_id' => $rowData[$id]['threshold_group_id'],
                        'name' => $rowData[$id]['name'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadPerKmTariffs(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'zone_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 15; break;
                                    case '2': $typeId = 16; break;
                                    case '3': $typeId = 17; break;
                                    case '4': $typeId = 18; break;
                                    case '5': $typeId = 19; break;
                                    case '6': $typeId = 20; break;
                                    case '7': $typeId = 21; break;
                                    case '8': $typeId = 22; break;
                                    case '9': $typeId = 23; break;
                                    case '10': $typeId = 24; break;
                                    case '11': $typeId = 25; break;
                                    case '12': $typeId = 26; break;
                                    case '13': $typeId = 27; break;
                                    case '14': $typeId = 28; break;
                                    case '15': $typeId = 29; break;
                                }
                                $rowData[$id]['zone_id'] = $typeId;
                                break;
                            case 'threshold':
                                $currentId = (string)$column;
                                $routeID = DB::table('forward_thresholds')->where('old_id',$currentId)->first();
                                $rowData[$id]['threshold'] = $routeID->id;
                                break;
                            case 'tariff':
                                $rowData[$id]['tariff'] = (string)$column;
                                break;
                        }
                    }
                    DB::table('per_km_tariffs')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'tariff_zone_id' => $rowData[$id]['zone_id'],
                        'forward_threshold_id' => $rowData[$id]['threshold'],
                        'tariff' => $rowData[$id]['tariff'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadInsideForwarding(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'city':
                                $currentId = (string)$column;
                                $routeID = DB::table('cities')->where('old_id',$currentId)->first();
                                $rowData[$id]['city'] = $routeID->id;
                                break;
                            case 'threshold':
                                $currentId = (string)$column;
                                $routeID = DB::table('forward_thresholds')->where('old_id',$currentId)->first();
                                $rowData[$id]['threshold'] = $routeID->id;
                                break;
                            case 'tariff':
                                $rowData[$id]['tariff'] = (string)$column;
                                break;
                        }
                    }
                    DB::table('inside_forwarding')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'city_id' => $rowData[$id]['city'],
                        'forward_threshold_id' => $rowData[$id]['threshold'],
                        'tariff' => $rowData[$id]['tariff'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadOutsideForwarding(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'point':
                                $currentId = (string)$column;
                                $routeID = DB::table('points')->where('old_id',$currentId)->first();
                                $rowData[$id]['point'] = $routeID->id;
                                break;
                            case 'threshold':
                                $currentId = (string)$column;
                                $routeID = DB::table('forward_thresholds')->where('old_id',$currentId)->first();
                                $rowData[$id]['threshold'] = $routeID->id;
                                break;
                            case 'tariff':
                                $rowData[$id]['tariff'] = (string)$column;
                                break;
                        }
                    }
                    DB::table('outside_forwardings')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'point' => $rowData[$id]['point'],
                        'forward_threshold_id' => $rowData[$id]['threshold'],
                        'tariff' => $rowData[$id]['tariff'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadRegions(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'code':
                                $rowData[$id]['code'] = (string)$column;
                                break;
                            case 'name':
                                $rowData[$id]['name'] = (string)$column;
                                break;
                            case 'fixed_tariff':
                                $rowData[$id]['fixed_tariff'] = (string)$column;
                                break;
                            case 'dist_tariff':
                                $rowData[$id]['dist_tariff'] = (string)$column;
                                break;
                            case 'inside_tariff':
                                $rowData[$id]['inside_tariff'] = (string)$column;
                                break;
                            case 'dest_city_id':
                                $currentId = (string)$column;
                                if($currentId == 'NULL'){
                                    $rowData[$id]['dest_city_id'] = null;
                                }else{
                                    $routeID = DB::table('cities')->where('old_id',$currentId)->first();
                                    $rowData[$id]['dest_city_id'] = $routeID->id;
                                }
                                break;
                            case 'threshold_group_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 6; break;
                                    case '2': $typeId = 7; break;
                                    case '3': $typeId = 8; break;
                                    case '4': $typeId = 9; break;
                                    case '5': $typeId = 10; break;
                                    case '6': $typeId = 11; break;
                                    case '7': $typeId = 12; break;
                                    case '8': $typeId = 13; break;
                                    case '13': $typeId = 14; break;
                                }
                                $rowData[$id]['threshold_group_id'] = $typeId;
                                break;
                            case 'tariff_zone_id':
                                switch ((string)$column) {
                                    case '1': $typeId = 15; break;
                                    case '2': $typeId = 16; break;
                                    case '3': $typeId = 17; break;
                                    case '4': $typeId = 18; break;
                                    case '5': $typeId = 19; break;
                                    case '6': $typeId = 20; break;
                                    case '7': $typeId = 21; break;
                                    case '8': $typeId = 22; break;
                                    case '9': $typeId = 23; break;
                                    case '10': $typeId = 24; break;
                                    case '11': $typeId = 25; break;
                                    case '12': $typeId = 26; break;
                                    case '13': $typeId = 27; break;
                                    case '14': $typeId = 28; break;
                                    case '15': $typeId = 29; break;
                                }
                                $rowData[$id]['tariff_zone_id'] = $typeId;
                                break;
                        }
                    }

                    DB::table('regions')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'code' => $rowData[$id]['code'],
                        'name' => $rowData[$id]['name'],
                        'fixed_tariff' => $rowData[$id]['fixed_tariff'],
                        'dist_tariff' => $rowData[$id]['dist_tariff'],
                        'inside_tariff' => $rowData[$id]['inside_tariff'],
                        'dest_city_id' => $rowData[$id]['dest_city_id'],
                        'threshold_group_id' => $rowData[$id]['threshold_group_id'],
                        'tariff_zone_id' => $rowData[$id]['tariff_zone_id'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadPoints(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'name':
                                $rowData[$id]['name'] = (string)$column;
                                break;
                            case 'region_code':
                                $currentId = (string)$column;
                                if($currentId == 'NULL'){
                                    $rowData[$id]['region_code'] = null;
                                }else{
                                    $routeID = DB::table('regions')->where('code',$currentId)->first();
                                    $rowData[$id]['region_code'] = $routeID->code;
                                }
                                break;
                            case 'city_id':
                                $currentId = (string)$column;
                                if($currentId == 'NULL'){
                                    $rowData[$id]['city_id'] = null;
                                }else{
                                    $routeID = DB::table('cities')->where('old_id',$currentId)->first();
                                    $rowData[$id]['city_id'] = $routeID->id;
                                }
                                break;
                            case 'distance':
                                $rowData[$id]['distance'] = (string)$column;
                                break;
                        }
                    }

                    DB::table('points')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'name' => $rowData[$id]['name'],
                        'region_code' => $rowData[$id]['region_code'],
                        'city_id' => $rowData[$id]['city_id'],
                        'distance' => $rowData[$id]['distance'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }

    public function uploadTerminals(Request $request) {
        $files = Storage::disk('import')->files();

        $nowTimestamp = Carbon::now()->format('Y.m.d H:i:s');

        foreach ($files as $fileKey=>$file)//перебираем все файлы
        {
            if ((preg_match("|.xml$|", $file)) OR (preg_match("|.XML$|", $file))) {//если это XML

                $dom = new \DOMDocument();
                $dom->loadXML(Storage::disk('import')->get($file));
                $xml = simplexml_import_dom($dom);
                $rowData = [];

                foreach ($xml->database->table as $rowKey=>$row) {

                    foreach ($row->column as $column) {
                        switch ($column->attributes()->name) {
                            case 'terminal_id':
                                $id = (string)$column;
                                $rowData[$id]['id'] = (string)$column;
                                break;
                            case 'name':
                                $rowData[$id]['name'] = (string)$column;
                                break;
                            case 'region_code':
                                $currentId = (string)$column;
                                if($currentId == 'NULL'){
                                    $rowData[$id]['region_code'] = null;
                                }else{
                                    $routeID = DB::table('regions')->where('code',$currentId)->first();
                                    if($routeID){
                                        $rowData[$id]['region_code'] = $routeID->code;
                                    }else{
                                        $rowData[$id]['region_code'] = null;
                                    }
                                }
                                break;
                            case 'city_id':
                                $currentId = (string)$column;
                                if($currentId == 'NULL'){
                                    $rowData[$id]['city_id'] = null;
                                }else{
                                    $routeID = DB::table('cities')->where('old_id',$currentId)->first();
                                    $rowData[$id]['city_id'] = $routeID->id;
                                }
                                break;
                            case 'address':
                                $rowData[$id]['address'] = (string)$column;
                                break;
                            case 'phone':
                                $rowData[$id]['phone'] = (string)$column;
                                break;
                            case 'geo_point':
                                $rowData[$id]['geo_point'] = $column;
                                break;
                        }
                    }
                    DB::table('terminals')->insert([
                        'old_id' => $rowData[$id]['id'],
                        'name' => $rowData[$id]['name'],
                        'region_code' => $rowData[$id]['region_code'],
                        'city_id' => $rowData[$id]['city_id'],
                        'address' => $rowData[$id]['address'],
                        'phone' => $rowData[$id]['phone'],
//                        'geo_point' => $rowData[$id]['geo_point'],
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ]);
                }
            }
        }
    }
}
