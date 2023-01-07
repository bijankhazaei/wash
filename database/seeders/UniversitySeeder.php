<?php

namespace Database\Seeders;

use App\Repositories\UniversityRepository;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * @var \App\Repositories\UniversityRepository
     */
    protected UniversityRepository $repository;

    public function __construct(UniversityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universities = [
            [
                'name'                => 'دانشگاه علوم پزشکی ساری',
                'area_id'             => 1,
                'total_centers_count' => 68
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی گلستان',
                'area_id'             => 1,
                'total_centers_count' => 37
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی تبریز',
                'area_id'             => 2,
                'total_centers_count' => 59
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی اردبیل',
                'area_id'             => 2,
                'total_centers_count' => 18
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی همدان',
                'area_id'             => 3,
                'total_centers_count' => 58
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی ایلام',
                'area_id'             => 3,
                'total_centers_count' => 19
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی اهواز',
                'area_id'             => 4,
                'total_centers_count' => 51
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی لرستان',
                'area_id'             => 4,
                'total_centers_count' => 16
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی شیراز',
                'area_id'             => 5,
                'total_centers_count' => 75
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی بندرعباس',
                'area_id'             => 5,
                'total_centers_count' => 25
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی زنجان',
                'area_id'             => 6,
                'total_centers_count' => 28
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی قم',
                'area_id'             => 6,
                'total_centers_count' => 31
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی اصفهان',
                'area_id'             => 7,
                'total_centers_count' => 49
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی یزد',
                'area_id'             => 7,
                'total_centers_count' => 10
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی کرمان',
                'area_id'             => 8,
                'total_centers_count' => 42
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی زاهدان',
                'area_id'             => 8,
                'total_centers_count' => 34
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی بیرجند',
                'area_id'             => 9,
                'total_centers_count' => 4
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی مشهد',
                'area_id'             => 9,
                'total_centers_count' => 46
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی تهران',
                'area_id'             => 10,
                'total_centers_count' => 20
            ],
            [
                'name'                => 'دانشگاه علوم پزشکی شهیدبهشتی',
                'area_id'             => 10,
                'total_centers_count' => 40
            ],
        ];

        foreach ($universities as $university) {
            $this->repository->create([
                'name'                => $university['name'],
                'area_id'             => $university['area_id'],
                'total_centers_count' => $university['total_centers_count'],
            ]);
        }
    }
}
