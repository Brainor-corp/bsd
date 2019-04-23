<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Terminal extends Model
{
    protected $fillable = [
        'name', 'short_name', 'city_id', 'region_code', 'street', 'house', 'geo_point',
    ];

    /**
     * @param Point $point
     */
    public function setGeoPointAttribute($point)
    {
        $coords = "-, -";
        if(!empty($point)) {
            $coords = explode(',', str_replace(' ', '', $point));
        }

        if(count($coords) == 2) {
            $x = trim($coords[0]);
            $y = trim($coords[1]);

            $query = "GeomFromText('POINT($x $y)')";
            $this->attributes['geo_point'] = DB::raw($query);
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function getGeoPointAttribute($value)
    {
        // cleanup the database response into a Point
        $response = explode(
            ' ',
            str_replace(
                [
                    "GeomFromText('",
                    "'",
                    'POINT(',
                    ')'
                ],
                '',
                $value
            )
        );

        return count($response) == 2 ? $response[0] . ', ' . $response[1] : '';
    }

    public function newQuery($excludeDeleted = true)
    {
        $raw = ' astext(geo_point) as geo_point ';
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'code');
    }

}
