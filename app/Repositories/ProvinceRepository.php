<?php

namespace App\Repositories;

use App\Models\Province;

class ProvinceRepository extends Repository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return Province::class;
    }
}
