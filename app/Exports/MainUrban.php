<?php

namespace App\Exports;

use App\Repositories\AnswerRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionRepository;
use Maatwebsite\Excel\Concerns\FromArray;

class MainUrban implements FromArray
{
    /**
     * @var \App\Repositories\QuestionRepository
     */
    protected QuestionRepository $questionRepository;
    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;
    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $healthCareCenterRepository;
    protected int $type;

    public function __construct(
        HealthCareCenterRepository $healthCareCenterRepository,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        int $type
    ) {
        $this->healthCareCenterRepository = $healthCareCenterRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->type = $type;
    }

    public function array() : array
    {
        $exportArray = [];
        $sections = [
            1 => 'Water',
            2 => 'Sanitation',
            3 => 'On-site sanitation facilities',
            4 => 'Hygiene ',
            5 => 'Health Care Waste Management',
            6 => 'Environmental Cleaning',
            7 => 'Electricity',
        ];

        for ($i = 1; $i <= 7; $i++) {
            $exportArray[] = [$sections[$i], '', '', ''];
            $sectionQuestions = $this->questionRepository->with('options')->findWhere(['section' => $i]);
            foreach ($sectionQuestions as $sectionQuestion) {
                $exportArray[] = ['', $sectionQuestion->title, '', ''];
                foreach ($sectionQuestion->options as $option) {
                    $answerForThisHCF = $this->answerRepository
                        ->whereHas('healthCareCenter', function ($q) {
                            $q->where('urban_type', $this->type);
                        })->findWhere([
                            'question_id' => $sectionQuestion->id,
                            'answer'      => $option->key
                        ])->count();

                    $exportArray[] = ['', '', $option->value, $answerForThisHCF];
                }
            }
        }

        return $exportArray;
    }
}
