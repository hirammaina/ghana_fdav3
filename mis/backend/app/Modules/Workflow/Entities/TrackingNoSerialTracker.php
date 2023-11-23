<?php
/**
 * Created by PhpStorm.
 * User: Kip
 * Date: 5/20/2019
 * Time: 3:13 PM
 */

namespace App\Modules\Workflow\Entities;


use Illuminate\Database\Eloquent\Model;

class TrackingNoSerialTracker extends Model
{
    protected $table = 'trackingnoprocesses_serials';
    protected $guarded = [];
    const UPDATED_AT = 'dola';
    const CREATED_AT = 'created_on';
}