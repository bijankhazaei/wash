<?php

namespace App\Console\Commands;

use App\Repositories\HealthCareCenterRepository;
use App\Repositories\QuestionnaireRepository;
use Illuminate\Console\Command;

class SyncLatLonQuestionnaire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:latlon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \App\Repositories\QuestionnaireRepository
     */
    protected QuestionnaireRepository $questionnaireRepository;
    /**
     * @var \App\Repositories\HealthCareCenterRepository
     */
    protected HealthCareCenterRepository $healthCareCenterRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        HealthCareCenterRepository $healthCareCenterRepository,
        QuestionnaireRepository $questionnaireRepository
    ) {
        parent::__construct();
        $this->healthCareCenterRepository = $healthCareCenterRepository;
        $this->questionnaireRepository = $questionnaireRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allCenters = $this->healthCareCenterRepository->all();
        foreach ($allCenters as $center) {
            $questionnaire = $this->questionnaireRepository->where(['health_care_center_id'=> $center->id])->first();
            if($questionnaire) {
                if ($questionnaire->latitude == null || $questionnaire->longitude == null) {
                    $this->questionnaireRepository->update([
                        'latitude'  => $center->latitude,
                        'longitude' => $center->longitude,
                    ],
                        $questionnaire->id
                    );
                }
            }
        }
    }
}
