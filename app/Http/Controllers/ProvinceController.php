<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * @var \App\Repositories\ProvinceRepository
     */
    protected ProvinceRepository $repository;
    /**
     * @var \App\Repositories\CityRepository
     */
    protected CityRepository $cityRepository;

    /**
     * ProvinceController constructor.
     *
     * @param \App\Repositories\ProvinceRepository $repository
     */
    public function __construct(
        ProvinceRepository $repository,
        CityRepository $cityRepository
    ) {
        $this->repository = $repository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces() : JsonResponse
    {
        $provinces = $this->repository->all();

        return response()->json($provinces);
    }

    public function getProvinceCites($id)
    {
        $cities = $this->cityRepository->where('province', $id)->get();

        return response()->json($cities);
    }
}
