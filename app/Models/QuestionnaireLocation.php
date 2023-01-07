<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireLocation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'questionnaires_locations';

    /**
     * @var string[]
     */
    protected $fillable = [
        'questionnaire_id',
        'latitude',
        'longitude',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questionnaire() : BelongsTo
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }
}
