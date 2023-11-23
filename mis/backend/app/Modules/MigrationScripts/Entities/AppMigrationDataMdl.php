<?php

namespace App\Modules\MigrationScripts\Entities;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class AppMigrationDataMdl implements ToModel
{
    /**
     * @param array $row
     *AppMigrationDataMdl.php
     * @return User|null
     */
    public function model(array $row)
    {
		
        return array('name');
    }
}
