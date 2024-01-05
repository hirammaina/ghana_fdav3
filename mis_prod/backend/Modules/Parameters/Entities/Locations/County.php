<?php


namespace Modules\Parameters\Entities\Locations;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Parameters\Entities\AbstractParameter;

class County extends AbstractParameter
{
    protected $fillable = [
        "name",
        "description",
        "is_enabled",
        "created_by",
        "altered_by",
        "dola",
        "country_id"
    ];

    protected $table = 'par_county';

    public function district() {
        return $this -> belongsTo("District");
    }

    public function subcounty() {
        return $this -> hasMany("SubCounty");
    }

    public static function getData($start, $limit, $doRetrieveAll, $filter) {
          $join = [
            "table_fk" => "districts",
            "table_col_alias" => "district_name",
            "table_fk_col" => "district_id"
        ];
        return parent::getFkOneJoin($start, $limit, $doRetrieveAll, __CLASS__, $join, $filter);
    }
}