<?php

namespace App\Repositories;

use App\Models\QuestionnaireLocation;

class QuestionnaireLocationRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return QuestionnaireLocation::class;
    }
}
