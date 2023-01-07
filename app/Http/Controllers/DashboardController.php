<?php

namespace App\Http\Controllers;

use App\Repositories\AreaRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\UniversityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @var \App\Repositories\UniversityRepository
     */
    protected UniversityRepository $universityRepository;

    /**
     * @var \App\Repositories\AreaRepository
     */
    protected AreaRepository $areaRepository;

    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $HealthCareCenterRepository;

    /**
     * DashboardController constructor.
     *
     * @param \App\Repositories\AreaRepository       $areaRepository
     * @param \App\Repositories\UniversityRepository $universityRepository
     */
    public function __construct(
        AreaRepository $areaRepository,
        UniversityRepository $universityRepository,
        HealthCareCenterRepository $HealthCareCenterRepository
    ) {
        $this->areaRepository = $areaRepository;
        $this->universityRepository = $universityRepository;
        $this->HealthCareCenterRepository = $HealthCareCenterRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUniversityData() : JsonResponse
    {
        $labels = [];
        $data = [];
        $universities = $this->universityRepository->all();

        foreach ($universities as $university) {
            $labels[] = $university->name;
            $data[] = round(((int) $university->questionnaire_count / (int) $university->total_centers_count) * 100, 2);
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreaData() : JsonResponse
    {
        $labels = [];
        $data = [];
        $areas = $this->areaRepository->all();

        foreach ($areas as $area) {
            $labels[] = $area->name;
            $data[] = round(((int) $area->questionnaire_count / (int) $area->total_centers_count) * 100, 2);
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }

    /**
     * @param int $province
     * @param int $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMapCenters(int $university_id, int $type) : JsonResponse
    {
        if ($university_id === 0) {
            $centers = $this->HealthCareCenterRepository->all();

            $export = [];

            foreach ($centers as $center) {
                $icon = '';
                if ($center->health_care_facility_type == 1) {
                    $icon = '/img/hcf.svg';
                } elseif ($center->health_care_facility_type == 2) {
                    $icon = '/img/hcf.svg';
                }
                $export[] = [
                    'id'      => $center->id,
                    'latLong' => [$center->latitude, $center->longitude],
                    'name'    => $center->type.' '.$center->name,
                    'icon'    => $icon,
                ];
            }

        } else {
            $centers = $this->HealthCareCenterRepository->whereHas('questionnaire', function ($q){
                $q->whereHas('answers' , function ($q) {
                    $q->where('question_id' , 41);
                });
            })->all();

            $export = [];

            foreach ($centers as $center) {
                $icon = '';
                if ($center->health_care_facility_type == 1) {
                    $icon = '/img/hcf.svg';
                } elseif ($center->health_care_facility_type == 2) {
                    $icon = '/img/hcf.svg';
                }
                $export[] = [
                    'id'      => $center->id,
                    'latLong' => [$center->latitude, $center->longitude],
                    'name'    => $center->type.' '.$center->name,
                    'icon'    => $icon,
                ];
            }

        }

        return response()->json($export);
    }
}
