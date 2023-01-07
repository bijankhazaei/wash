<?php

namespace App\Repositories;

use App\Models\Area;

class AreaRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Area::class;
    }
}
