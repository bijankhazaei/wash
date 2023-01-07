<?php

namespace App\Http\Controllers;

use App\Repositories\AnswerRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionnaireLocationRepository;
use App\Repositories\QuestionnaireRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthCareCenterController extends Controller
{
    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $repository;

    /**
     * @var \App\Repositories\QuestionnaireRepository
     */
    protected QuestionnaireRepository $questionnaireRepository;

    /**
     * @var \App\Repositories\AnswerRepository
     */
    protected AnswerRepository $answerRepository;
    /**
     * @var \App\Repositories\UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var \App\Repositories\QuestionnaireLocationRepository
     */
    protected QuestionnaireLocationRepository $questionnaireLocationRepository;

    /**
     * HealthCareCenterController constructor.
     *
     * @param \App\Repositories\HealthCareCenterRepository $repository
     */
    public function __construct(
        UserRepository $userRepository,
        HealthCareCenterRepository $repository,
        QuestionnaireRepository $questionnaireRepository,
        QuestionnaireLocationRepository $questionnaireLocationRepository,
        AnswerRepository $answerRepository
    ) {
        $this->repository = $repository;
        $this->questionnaireRepository = $questionnaireRepository;
        $this->answerRepository = $answerRepository;
        $this->userRepository = $userRepository;
        $this->questionnaireLocationRepository = $questionnaireLocationRepository;

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request) : JsonResponse
    {
        $centers = $this->repository->with(['city', 'university'])->all();

        return response()->json($centers);
    }

    public function getListByUniversity($id) : JsonResponse
    {
        $centers = $this->repository->with(['city', 'university'])
            ->findWhere(['university_id' => $id]);

        return response()->json($centers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewHealthCareCenter(Request $request) : JsonResponse
    {
        $healthCareCenter = $this->repository->create([
            'area_id'                      => $request->get('area_id'),
            'university_id'                => $request->get('university_id'),
            'city_id'                      => $request->get('city_id'),
            'latitude'                     => $request->get('latitude'),
            'longitude'                    => $request->get('longitude'),
            'name'                         => $request->get('name'),
            'health_care_facility_type'    => $request->get('health_care_facility_type'),
            'non_hospital_facilities_type' => $request->get('non_hospital_facilities_type'),
            'managerial_type'              => $request->get('managerial_type'),
            'urban_type'                   => $request->get('urban_type'),
        ]);

        return response()->json([
            'data' => $healthCareCenter,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editHealthCareCenter(Request $request, $id) : JsonResponse
    {
        $healthCareCenter = $this->repository->update([
            'area_id'                      => $request->get('area_id'),
            'university_id'                => $request->get('university_id'),
            'city_id'                      => $request->get('city_id'),
            'latitude'                     => $request->get('latitude'),
            'longitude'                    => $request->get('longitude'),
            'name'                         => $request->get('name'),
            'health_care_facility_type'    => $request->get('health_care_facility_type'),
            'non_hospital_facilities_type' => $request->get('non_hospital_facilities_type'),
            'managerial_type'              => $request->get('managerial_type'),
            'urban_type'                   => $request->get('urban_type'),
            'confirm'                      => $request->get('confirm'),
        ], $id);

        return response()->json([
            'data' => $healthCareCenter,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEnumeratorCenters(Request $request, $city) : JsonResponse
    {
        $user = $request->user();

        if ($city == 'all') {
            $centers = $this->repository->with(['area', 'university'])
                ->findWhere(['university_id' => $user->university_id]);
        } else {
            $centers = $this->repository->with(['area', 'university'])
                ->findWhere(['city_id' => (int) $city]);
        }

        $currentUser = $this->userRepository->with('cities')->find($user->id);

        return response()->json([
            'centers' => $centers->where('confirm', 1),
            'cities'  => $currentUser->cities,
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getHealthCareCenter($id) : JsonResponse
    {
        $center = $this->repository->with([
            'area',
            'university',
            'questionnaire',
            'city' => function ($q) {
                $q->with(['province']);
            }
        ])->find($id);

        return response()->json($center);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function saveQuestionnaire(Request $request, int $id) : JsonResponse
    {
        $request->validate([
            'interviewee_name'     => 'required',
            'interviewee_position' => 'required',
            'interviewee_phone'    => 'required|numeric',
            'nominal_beds'         => 'required|numeric',
            'active_beds'          => 'required|numeric',
            'men_staffs'           => 'required|numeric',
            'women_staffs'         => 'required|numeric',
        ]);

        $healthCareCenter = $this->repository->update([
            'nominal_beds' => $request->get('nominal_beds'),
            'active_beds'  => $request->get('active_beds'),
            'men_staffs'   => $request->get('men_staffs'),
            'women_staffs' => $request->get('women_staffs'),
            'q_status'     => 1
        ], $id);

        if ($this->questionnaireRepository->where('health_care_center_id', $id)->exists()) {
            $this->questionnaireRepository->findWhere(['health_care_center_id' => $id])->first()
                ->update([
                    'user_id'              => $request->user()->id,
                    'interviewee_name'     => $request->get('interviewee_name'),
                    'interviewee_position' => $request->get('interviewee_position'),
                    'interviewee_phone'    => $request->get('interviewee_phone'),
                ]);
            $questionnaire = $this->questionnaireRepository->findWhere(['health_care_center_id' => $id])->first();
            $this->questionnaireLocationRepository->create([
                'questionnaire_id' => $questionnaire->id,
                'latitude'         => $request->get('latitude'),
                'longitude'        => $request->get('longitude'),
            ]);
        } else {
            $this->questionnaireRepository->create([
                'user_id'               => $request->user()->id,
                'dou_date'              => Carbon::now(),
                'interviewee_name'      => $request->get('interviewee_name'),
                'interviewee_position'  => $request->get('interviewee_position'),
                'interviewee_phone'     => $request->get('interviewee_phone'),
                'latitude'              => $request->get('latitude'),
                'longitude'             => $request->get('longitude'),
                'health_care_center_id' => $healthCareCenter->id
            ]);
        }

        return response()->json($healthCareCenter);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveQuestionnaireAnswers(Request $request, int $id) : JsonResponse
    {
        $request->validate([
            'answers' => 'required'
        ]);

        $answers = $request->get('answers');

        $questionnaire = $this->questionnaireRepository
            ->findWhere(['health_care_center_id' => $id])
            ->first();

        $result = [];

        $lastQuestion = 0;

        foreach ($answers as $key => $item) {
            $questionId = substr($key, 2);
            $result[] = $this->answerRepository->updateOrCreate([
                'health_care_center_id' => $id,
                'question_id'           => (int) $questionId,
            ], [
                'answer'           => $item,
                'questionnaire_id' => $questionnaire->id,
            ]);

            $lastQuestion = $questionId;
        }

        if ($lastQuestion == 41) {
            $this->repository->update([
                'q_status' => 2
            ], $id);
        }

        return response()->json($result);
    }
}
