<?php

namespace App\Http\Controllers;

use App\City;
use App\InsideForwarding;
use App\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PricesController extends Controller {
    public function pricesPage(Request $request) {
        $shipCityId = $request->get('ship_city') ?? 53;
        $destCityId = $request->get('dest_city') ?? 78;

        $route = Route::where([
            ['ship_city_id', $shipCityId],
            ['dest_city_id', $destCityId]
        ])->with('route_tariffs.rate', 'route_tariffs.threshold')->first();

        $insideForwardings = InsideForwarding::with('forwardThreshold', 'city')
            ->has('forwardThreshold')
            ->whereIn('city_id', [$shipCityId, $destCityId])
            ->get();

        $action = $request->get('action') ?? 'show';

        if($action == 'show') {
            $shipCities = City::where('is_ship', true)->get();
            $destCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $shipCityId))->get();

            return view('v1.pages.prices.prices-page')
                ->with(compact('route','insideForwardings', 'shipCities', 'destCities', 'shipCityId', 'destCityId'));
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator('Балтийская служба доставки')
                ->setLastModifiedBy('Балтийская служба доставки')
                ->setTitle("Прайс лист $route->name")
                ->setSubject("Прайс лист $route->name")
                ->setDescription("Прайс лист $route->name");

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Отчеты');

            // Вычисляем кол-во ячеек, которое должен занимать заголовок тарифов
            $tariffsTitleRange = [
                Coordinate::stringFromColumnIndex(1),
                Coordinate::stringFromColumnIndex(
                    3 + // 3 ячейки есть всегда
                        $route->route_tariffs->where('rate.slug', 'ves')->count() + // кол-во ячеек для цен по весу
                        $route->route_tariffs->where('rate.slug', 'obem')->count() // кол-во ячеек для цен по объёму
                )
            ];
            $sheet->mergeCells("$tariffsTitleRange[0]1:$tariffsTitleRange[1]1");
            $sheet->setCellValue('A1', "Тарифы на грузоперевозки маршрута $route->name");

            $sheet->setCellValue('A3', "Бандероль до 0,01 м3 до 2 кг");
            $sheet->mergeCells("A3:A4");
            $sheet->getStyle('A3')->getAlignment()->setWrapText(true);

            $sheet->setCellValue('B3', "мин. стоимость руб.");
            $sheet->mergeCells("B3:B4");
            $sheet->getStyle('B3')->getAlignment()->setWrapText(true);

            // Вычисляем ширину ячейки для заголовка стоимости за кг
            $tariffWeightPriceTitleEnd = 'C';
            if($route->route_tariffs->where('rate.slug', 'ves')->count() > 0) {
                $tariffWeightPriceTitleStart = 'C';
                $tariffWeightPriceTitleEnd = Coordinate::stringFromColumnIndex(
                    Coordinate::columnIndexFromString('C') +
                    $route->route_tariffs->where('rate.slug', 'ves')->count() - 1
                );
                $sheet->mergeCells("C3:$tariffWeightPriceTitleEnd" . '3');
                $sheet->setCellValue($tariffWeightPriceTitleStart . '3', "стоимость за 1кг руб.");
                $sheet->getStyle($tariffWeightPriceTitleStart . '3')->getAlignment()->setWrapText(true);

                $i = 0;
                foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff) {
                    $pricesIteratorCell = Coordinate::stringFromColumnIndex(
                        Coordinate::columnIndexFromString($tariffWeightPriceTitleStart) + $i
                    );
                    $sheet->setCellValue($pricesIteratorCell . '4', "до " . $routeTariff->threshold->value . 'кг');
                    $sheet->getStyle($pricesIteratorCell . '4')->getAlignment()->setWrapText(true);
                    $i++;
                }
            }

            // Вычисляем ширину ячейки для заголовка стоимости за м3
            $tariffVolumePriceTitleEnd = $tariffWeightPriceTitleEnd;
            if($route->route_tariffs->where('rate.slug', 'obem')->count() > 0) {
                $tariffVolumePriceTitleStart = Coordinate::stringFromColumnIndex(
                    Coordinate::columnIndexFromString($tariffWeightPriceTitleEnd) + ($tariffWeightPriceTitleEnd === 'C' ? 0 : 1)
                );
                $tariffVolumePriceTitleEnd = Coordinate::stringFromColumnIndex(
                    Coordinate::columnIndexFromString($tariffVolumePriceTitleStart) +
                    $route->route_tariffs->where('rate.slug', 'obem')->count() - 1
                );
                $sheet->mergeCells($tariffVolumePriceTitleStart . '3' . ':' . $tariffVolumePriceTitleEnd . '3');
                $sheet->setCellValue($tariffVolumePriceTitleStart . '3', "стоимость за 1м3 руб.");
                $sheet->getStyle($tariffVolumePriceTitleStart . '3')->getAlignment()->setWrapText(true);

                $i = 0;
                foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff) {
                    $pricesIteratorCell = Coordinate::stringFromColumnIndex(
                        Coordinate::columnIndexFromString($tariffVolumePriceTitleStart) + $i
                    );
                    $sheet->setCellValue($pricesIteratorCell . '4', "до " . $routeTariff->threshold->value . 'м3');
                    $sheet->getStyle($pricesIteratorCell . '4')->getAlignment()->setWrapText(true);
                    $i++;
                }
            }

            // Вычисляем позичию ячейки для заголовка минимального срока доставки
            $tariffMinDaysTitlePos = Coordinate::stringFromColumnIndex(
                Coordinate::columnIndexFromString($tariffVolumePriceTitleEnd) + ($tariffVolumePriceTitleEnd === 'C' ? 0 : 1)
            );
            $sheet->setCellValue($tariffMinDaysTitlePos . '3', "мин. срок доставки");
            $sheet->mergeCells($tariffMinDaysTitlePos . '3' . ':' . $tariffMinDaysTitlePos . '4');
            $sheet->getStyle($tariffMinDaysTitlePos . '3')->getAlignment()->setWrapText(true);

            // Заполняем значения цен тарифа
            $tariffPrices = [
                $route->wrapper_tariff,
                $route->min_cost
            ];

            foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff) {
                $tariffPrices[] = $routeTariff->price;
            }

            foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff) {
                $tariffPrices[] = $routeTariff->price;
            }

            $tariffPrices[] = $route->delivery_time;

            foreach($tariffPrices as $key => $tariffPrice) {
                $column = Coordinate::stringFromColumnIndex($key + 1);
                $sheet->setCellValue($column . '5', $tariffPrice);
            }

            $writer = new Xlsx($spreadsheet);

            $name = md5("Прайс лист $route->name" . time()) . '.xlsx';
            $path = storage_path('app/public/prices/');

            File::makeDirectory($path, $mode = 0777, true, true);
            $writer->save($path . $name);

            return response()->download($path . $name, "Прайс лист $route->name" . '.xlsx')->deleteFileAfterSend(true);
        }
    }

    public function getDestinationCities(Request $request) {
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $request->get('ship_city')))
            ->orderBy('name')
            ->get();

        return view('v1.partials.prices.destination-cities')->with(compact('destinationCities'));
    }

}
