<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'areas';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'total_centers_count',
    ];

    protected $appends = ['questionnaire_count'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function universities() : HasMany
    {
        return $this->hasMany(University::class, 'area_id');
    }

    public function getQuestionnaireCountAttribute()
    {
        $universities = $this->universities()->whereHas('healthCareCenters', function ($q) {
            $q->whereHas('questionnaire', function ($q){
                $q->whereHas('answers' , function ($q) {
                    $q->where('question_id' , 41);
                });
            });
        })->get();

        $total = 0;

        foreach ($universities as $university) {
            $total += $university->questionnaire_count;
        }

        return $total;
    }
}
