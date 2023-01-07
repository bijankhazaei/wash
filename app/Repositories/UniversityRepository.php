<?php

namespace App\Repositories;

use App\Models\University;

class UniversityRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return University::class;
    }
}
