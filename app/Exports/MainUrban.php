<?php

namespace App\Exports;

use App\Repositories\AnswerRepository;
use App\Repositories\AreaRepository;
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
    protected AreaRepository $areaRepository;

    public function __construct(
        HealthCareCenterRepository $healthCareCenterRepository,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        AreaRepository $areaRepository,
        int $type
    ) {
        $this->healthCareCenterRepository = $healthCareCenterRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->type = $type;
        $this->areaRepository = $areaRepository;
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

                    $areas = $this->areaRepository->all();

                    $areaReport = [];

                    foreach ($areas as $area) {
                        $areaReport[] = $this->answerRepository->whereHas('healthCareCenter', function ($q) use ($area){
                            $q->where(['area_id' => $area->id , 'urban_type' =>  $this->type]);
                            })->findWhere([
                                'question_id' => $sectionQuestion->id,
                                'answer' => $option->key
                            ])->count();
                    }

                    $exportArray[] = ['', '', $option->value, $answerForThisHCF, ...$areaReport];
                }
            }
        }

        return $exportArray;
    }
}
