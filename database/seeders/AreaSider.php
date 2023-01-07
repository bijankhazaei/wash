<?php

namespace Database\Seeders;

use App\Repositories\AreaRepository;
use Illuminate\Database\Seeder;

class AreaSider extends Seeder
{
    /**
     * @var \App\Repositories\AreaRepository
     */
    protected AreaRepository $repository;

    public function __construct(AreaRepository $repository)
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
        $areas = [
            [
                'name'                => 'منطقه 1',
                'total_centers_count' => 105,
            ],
            [
                'name'                => 'منطقه 2',
                'total_centers_count' => 77,
            ],
            [
                'name'                => 'منطقه 3',
                'total_centers_count' => 77,
            ],
            [
                'name'                => 'منطقه 4',
                'total_centers_count' => 67,
            ],
            [
                'name'                => 'منطقه 5',
                'total_centers_count' => 100,
            ],
            [
                'name'                => 'منطقه 6',
                'total_centers_count' => 59,
            ],
            [
                'name'                => 'منطقه 7',
                'total_centers_count' => 59,
            ],
            [
                'name'                => 'منطقه 8',
                'total_centers_count' => 76,
            ],
            [
                'name'                => 'منطقه 9',
                'total_centers_count' => 50,
            ],
            [
                'name'                => 'منطقه 10',
                'total_centers_count' => 60,
            ],
        ];

        foreach ($areas as $area) {
            $this->repository->create([
                'name'                => $area['name'],
                'total_centers_count' => $area['total_centers_count'],
            ]);
        }
    }
}
