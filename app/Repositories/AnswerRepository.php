<?php

namespace App\Repositories;

use App\Models\Answer;

class AnswerRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Answer::class;
    }
}
