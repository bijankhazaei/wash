<?php

namespace Database\Seeders;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * @var \App\Repositories\UserRepository
     */
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
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
        DB::table('users')->insert([
            'name'     => 'Bijan',
            'email'    => 'bijan@wash.io',
            'password' => bcrypt('123456')
        ]);

        $administrator = User::query()->where('email', 'bijan@wash.io')->first();

        $administrator->assignRole('administrator');

        $users = [
            [
                'name'  => 'AL-EMAM, Rola',
                'email' => 'alemamr@who.int',
                'role'  => 'who',
            ],
            [
                'name'  => 'Shakkour, Mohammad',
                'email' => 'shakkourm@who.int',
                'role'  => 'who',
            ],
            [
                'name'  => 'KHALEGHY RAD, Mona',
                'email' => 'khaleghym@who.int',
                'role'  => 'who',
            ],
            [
                'name'  => 'YADEGARI, Mehrdad',
                'email' => 'yadegarim@who.int',
                'role'  => 'who',
            ],
            [
                'name'  => 'Elahi, Tayebeh',
                'email' => 'elahi.tayebeh@yahoo.com',
                'role'  => 'who',
            ],
            [
                'name'  => 'Sheikholeslam, Samira',
                'email' => 'sheikholeslami820@yahoo.com',
                'role'  => 'who',
            ],
            [
                'name'  => 'Khazei, Mohammad',
                'email' => 'khazaei57@gmail.com',
                'role'  => 'core-team',
            ],
            [
                'name'  => 'Mahjub, Hossein',
                'email' => 'mahjub@umsha.ac.ir',
                'role'  => 'core-team',
            ],
            [
                'name'  => 'Rahmani, Alireza',
                'email' => 'rah1340@yahoo.com',
                'role'  => 'core-team',
            ],
            [
                'name'  => 'Chavoshi, Sonia',
                'email' => 'so.chavoshi@yahoo.com',
                'role'  => 'core-team',
            ],
            [
                'name'  => 'Roshani, Maryam',
                'email' => 'maroshani35@gmail.com',
                'role'  => 'core-team',
            ],
            [
                'name'          => 'Rafati, Lida',
                'email'         => 'l.rafati@yahoo.com',
                'role'          => ['core-team', 'enumerator'],
            ],
        ];

        foreach ($users as $user) {
            $createdUser = $this->repository->create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => bcrypt('washinhcf')
            ]);
            $newUser = User::find($createdUser->id);
            $newUser->assignRole($user['role']);
        }
    }
}
