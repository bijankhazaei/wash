<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'questionnaires';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'health_care_center_id',
        'dou_date',
        'interviewee_name',
        'interviewee_phone',
        'interviewee_position',
        'latitude',
        'longitude',
        'values',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function healthCareCenter() : BelongsTo
    {
        return $this->belongsTo(HealthCareCenter::class, 'health_care_center_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class, 'questionnaire_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations() : HasMany
    {
        return $this->hasMany(QuestionnaireLocation::class, 'questionnaire_id', 'id');
    }
}
