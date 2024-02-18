<?php


namespace Modules\Parameters\Entities\Locations;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Parameters\Entities\AbstractParameter;

class SubCounty extends AbstractParameter
{
    protected $fillable = [
        "name",
        "description",
        "is_enabled",
        "created_by",
        "altered_by",
        "dola"
    ];

    protected $table = 'par_sub_county';

    public function County() {
        return $this -> belongsTo("County");
    }


    public static function getData($start, $limit, $doRetrieveAll, $filters) {
        $rawSql = "par_sub_county.*, par_county.name as county_name,par_districts.name as district_name, par_regions.name as region_name,par_countries.name as country_name";
        $db = DB::table("par_sub_county")
            ->leftJoin("par_county", "par_sub_county.county_id", '=', "par_county.id")
            ->leftJoin("par_districts", "par_county.district_id", '=', "par_districts.id")
            ->leftJoin("par_regions", "par_districts.region_id", '=', "par_regions.id")
            ->leftJoin("par_countries", "par_regions.country_id", '=', "par_countries.id")
            ->select(DB::raw($rawSql));

        return self::getFk($start, $limit, $doRetrieveAll, __CLASS__, $db, $filters);
    }
}