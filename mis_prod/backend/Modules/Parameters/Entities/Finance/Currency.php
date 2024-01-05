<?php


namespace Modules\Parameters\Entities\Finance;

use Modules\Parameters\Entities\AbstractParameter;
use Modules\Parameters\Entities\GetDataTrait;

class Currency extends AbstractParameter
{
    protected $fillable = [
        "name",
        "code",
        "description",
        "created_by",
        "created_at",
        "is_enabled",
        "dola",
        "altered_by"
    ];

    protected $table = "par_currencies";

    public function exchangeRates() {
        $this -> hasMany("ExchangeRate");
    }

    use GetDataTrait;
}