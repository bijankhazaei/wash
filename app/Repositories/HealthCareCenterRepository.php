<?php

namespace App\Repositories;

use App\Models\HealthCareCenter;

class HealthCareCenterRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return HealthCareCenter::class;
    }
}
