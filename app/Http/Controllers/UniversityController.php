<?php

namespace App\Http\Controllers;

use App\Repositories\UniversityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * @var \App\Repositories\UniversityRepository
     */
    protected UniversityRepository $universityRepository;

    public function __construct(UniversityRepository $universityRepository)
    {
        $this->universityRepository = $universityRepository;
    }

    public function getList() : JsonResponse
    {
        $all = $this->universityRepository->all();

        return response()->json($all);
    }
}
