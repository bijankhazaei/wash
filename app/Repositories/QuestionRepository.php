<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Question::class;
    }
}
