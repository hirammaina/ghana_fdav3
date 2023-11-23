<?php

namespace App\Modules\AuditTrail\Entities;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = [];
    protected $connection = 'TRAILDB_CONNECTION';

}
