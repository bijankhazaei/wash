<?php

namespace App\Exports;

use App\Repositories\AnswerRepository;
use App\Repositories\QuestionRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class MainReportExport implements FromArray
{
    /**
     * @var \App\Repositories\QuestionRepository
     */
    protected QuestionRepository $questionRepository;
    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    public function array() : array
    {
        $questions = $this->questionRepository->with('options')->all();
        $answers = [];
        foreach ($questions as $question) {
            $answers[$question->id] = [
              'question' => $question->title
            ];
            foreach ($question->options as $option) {
                $answers[$question->id][$option->key] = $this->answerRepository
                    ->findWhere([
                        'answer'      => $option->key,
                        'question_id' => $question->id,
                    ])->groupBy('questionnaire_id')->count();
            }
        }
        return $answers;
        
    }
}
