<?php

namespace App\Http\Controllers;

use App\Repositories\AnswerRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionnaireRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    /**
     * @var \App\Repositories\QuestionRepository
     */
    protected QuestionRepository $questionRepository;

    /**
     * @var \App\Repositories\QuestionnaireRepository
     */
    protected QuestionnaireRepository $questionnaireRepository;

    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;
    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $healthCareCenterRepository;

    /**
     * QuestionnaireController constructor.
     *
     * @param \App\Repositories\QuestionRepository      $questionRepository
     * @param \App\Repositories\QuestionnaireRepository $questionnaireRepository
     */
    public function __construct(
        QuestionRepository $questionRepository,
        QuestionnaireRepository $questionnaireRepository,
        AnswerRepository $answerRepository,
        HealthCareCenterRepository $healthCareCenterRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->questionnaireRepository = $questionnaireRepository;
        $this->answerRepository = $answerRepository;
        $this->healthCareCenterRepository = $healthCareCenterRepository;
    }

    /**
     * @param $section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestions($section) : JsonResponse
    {
        $result = $this->questionRepository->with('options')
            ->where('section', $section)
            ->get();

        return response()->json($result);
    }

    /**
     * @param $center_id
     * @param $section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnswers($center_id, $section) : JsonResponse
    {
        $sectionQuestions = $this->questionRepository->where('section', $section)
            ->pluck('id');
        $answers = $this->answerRepository->where('health_care_center_id', $center_id)
            ->whereIn('question_id', $sectionQuestions)->get()->pluck('answer', 'question_id')->toArray();

        $result = [];
        foreach ($sectionQuestions as $question) {
            $result[$question] = $answers[$question] ?? null;
        }

        return response()->json($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompletedQuestionnaires($university_id) : JsonResponse
    {
        if ($university_id == 0) {
            $findWhere = [
                ['q_status', '>', 0]
            ];
        } else {
            {
                $findWhere = [
                    'university_id' => $university_id,
                    ['q_status', '>', 0]
                ];
            }
        }

        $centers = $this->healthCareCenterRepository
            ->with(['city', 'university'])
            ->whereHas('questionnaire', function ($q){
                $q->whereHas('answers' , function ($q) {
                    $q->where('question_id' , 41);
                });
            })->findWhere($findWhere);

/*        foreach ($centers as $center) {
            $this->healthCareCenterRepository->update([
                'q_status' => 2
            ], $center->id);
        }*/

        return response()->json($centers);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestionnaire($id) : JsonResponse
    {
        $questionnaire = $this->questionnaireRepository
            ->with([
                'user',
                'locations',
                'healthCareCenter' => function ($q) {
                    $q->with([
                        'city' => function ($q) {
                            $q->with('province');
                        },
                        'university',
                        'area'
                    ]);
                },
                'answers'          => function ($q) {
                    $q->with([
                        'question' => function ($q) {
                            $q->with('options');
                        }
                    ]);
                }
            ])->findWhere(['health_care_center_id' => $id])->first();

        return response()->json($questionnaire);
    }
}
