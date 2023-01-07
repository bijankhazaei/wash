<?php

namespace App\Exports;

use App\Repositories\AnswerRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DetailedGovernmentExport implements WithMultipleSheets
{

    protected int $type;
    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $healthCareCenterRepository;
    /**
     * @var \App\Repositories\QuestionRepository
     */
    protected QuestionRepository $questionRepository;
    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;

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

    public function sheets() : array
    {
        $healthCareCenters = $this->healthCareCenterRepository->has('questionnaire')
            ->findWhere([
                'managerial_type' => $this->type
            ]);
        $sheets = [];
        foreach ($healthCareCenters as $healthCareCenter) {
            $sheets[] = new DetailedExportSheet(
                $healthCareCenter,
                $this->questionRepository,
                $this->answerRepository
            );
        }

        return $sheets;
    }
}
