<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetAllPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wash:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \App\Repositories\UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = $this->userRepository->whereHas('roles', function ($q) {
            $q->where('name', 'enumerator');
        })->all();

        foreach ($users as $user) {
            $this->userRepository->update(
                [
                    'password' => bcrypt('washinhcf')
                ],
                $user->id
            );
        }
    }
}
