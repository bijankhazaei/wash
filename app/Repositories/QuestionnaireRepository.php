<?php

namespace App\Repositories;

use App\Models\Questionnaire;

class QuestionnaireRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Questionnaire::class;
    }
}
