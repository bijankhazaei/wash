<?php

namespace App\Http\Controllers;

use App\Exports\DetailedExport;
use App\Exports\DetailedGovernmentExport;
use App\Exports\DetailedHospitalExport;
use App\Exports\DetailedUrbanExport;
use App\Exports\MainAreas;
use App\Exports\MainGovernment;
use App\Exports\MainHospital;
use App\Exports\MainReportExport;
use App\Exports\MainUrban;
use App\Imports\HealthCareCenterImport;
use App\Repositories\AnswerRepository;
use App\Repositories\AreaRepository;
use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
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
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        HealthCareCenterRepository $healthCareCenterRepository,
        AreaRepository $areaRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->healthCareCenterRepository = $healthCareCenterRepository;
        $this->areaRepository = $areaRepository;
    }

    public function importHealthCareCenters(Request $request) : JsonResponse
    {
        $request->validate([
            'health_care_centers_file' => 'required|mimes:xlsx'
        ]);

        $xlsxFile = $request->file('health_care_centers_file')->storeAs('hcc', 'hcc.xlsx');

        Excel::import(new HealthCareCenterImport, $xlsxFile);

        return response()->json('true');
    }

    public function getMainExcelFile() : BinaryFileResponse
    {
        return Excel::download(new MainReportExport($this->questionRepository, $this->answerRepository),
            'mainReport.xlsx');
    }

    public function getMainExcelFileApi() : JsonResponse
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
                    ])->count();
            }
        }

        return response()->json(compact('answers'));
    }

    public function detailedReportHospital($hospital_type) : BinaryFileResponse
    {
        if ($hospital_type == 1) {
            $fileName = 'detailedReportHospital.xlsx';
        } else {
            $fileName = 'detailedReportNonHospital.xlsx';
        }

        return Excel::download(
            new DetailedHospitalExport(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $hospital_type
            ),
            $fileName);
    }

    public function detailedReportGovernment($government_type) : BinaryFileResponse
    {
        if ($government_type == 1) {
            $fileName = 'detailedReportGovernmental.xlsx';
        } else {
            $fileName = 'detailedReportNonGovernmental.xlsx';
        }

        return Excel::download(
            new DetailedGovernmentExport(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $government_type),
            $fileName);
    }

    public function detailedReportUrban($urban_type) : BinaryFileResponse
    {
        if ($urban_type == 1) {
            $fileName = 'detailedReportNonUrban.xlsx';
        } else {
            $fileName = 'detailedReportUrban.xlsx'; //
        }

        return Excel::download(
            new DetailedUrbanExport(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $urban_type),
            $fileName);
    }

    public function mainReportHospital($hospital_type) : BinaryFileResponse
    {
        if ($hospital_type == 1) {
            $fileName = 'mainReportHospital.xlsx';
        } else {
            $fileName = 'mainReportNonHospital.xlsx';
        }

        return Excel::download(
            new MainHospital(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $this->areaRepository,
                $hospital_type),
            $fileName);
    }

    public function mainReportGovernment($government_type) : BinaryFileResponse
    {
        if ($government_type == 1) {
            $fileName = 'mainReportGovernmental.xlsx';
        } else {
            $fileName = 'mainReportNonGovernmental.xlsx';
        }

        return Excel::download(
            new MainGovernment(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $this->areaRepository,
                $government_type),
            $fileName);
    }

    public function mainReportArea() : BinaryFileResponse
    {
        $fileName = 'mainReportArea.xlsx';

        return Excel::download(
            new MainAreas(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $this->areaRepository,
            ),
            $fileName);
    }

    public function mainReportUrban($urban_type) : BinaryFileResponse
    {
        if ($urban_type == 1) {
            $fileName = 'mainReportNonUrban.xlsx';
        } else {
            $fileName = 'mainReportUrban.xlsx';
        }

        return Excel::download(
            new MainUrban(
                $this->healthCareCenterRepository,
                $this->questionRepository,
                $this->answerRepository,
                $this->areaRepository,
                $urban_type),
            $fileName);
    }
}
