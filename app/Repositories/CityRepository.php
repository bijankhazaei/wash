<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return City::class;
    }
}
