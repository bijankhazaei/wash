<?php

namespace App\Exports;

use App\Repositories\AnswerRepository;
use App\Repositories\AreaRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class MainAreas implements FromArray
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
    protected AreaRepository $areaRepository;

    public function __construct(
        HealthCareCenterRepository $healthCareCenterRepository,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        AreaRepository $areaRepository
    ) {
        $this->healthCareCenterRepository = $healthCareCenterRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
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
                    $answerForThisHCF = $this->answerRepository->findWhere([
                            'question_id' => $sectionQuestion->id,
                            'answer' => $option->key
                        ])->count();

                    $areas = $this->areaRepository->all();

                    $areaReport = [];

                    foreach ($areas as $area) {
                        $areaReport[] = $this->answerRepository->whereHas('healthCareCenter', function ($q) use ($area){
                                $q->where('area_id', $area->id);
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
