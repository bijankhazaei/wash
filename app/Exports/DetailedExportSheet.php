<?php

namespace App\Exports;

use App\Models\HealthCareCenter;
use App\Repositories\AnswerRepository;
use App\Repositories\QuestionRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailedExportSheet implements FromArray, WithTitle
{
    /**
     * @var \App\Models\HealthCareCenter
     */
    protected HealthCareCenter $healthCareCenter;

    /**
     * @var \App\Repositories\QuestionRepository
     */
    protected QuestionRepository $questionRepository;
    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;

    public function __construct(
        HealthCareCenter $healthCareCenter,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->healthCareCenter = $healthCareCenter;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * @return array
     */
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
                $answerForThisHCF = $this->answerRepository
                    ->findWhere([
                        'question_id'           => $sectionQuestion->id,
                        'health_care_center_id' => $this->healthCareCenter->id,
                        'questionnaire_id'      => $this->healthCareCenter->questionnaire->id,
                    ])->first();
                foreach ($sectionQuestion->options as $option) {
                    $answerFlag = '';
                    if($answerForThisHCF) {
                        $answerFlag = $answerForThisHCF->answer == $option->key ? 1 : '';
                    }
                    $exportArray[] = ['', '', $option->value, $answerFlag];
                }
            }
        }

        return $exportArray;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->healthCareCenter->name;
    }
}

