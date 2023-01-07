<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Answer extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'answers';

    /**
     * @var string[]
     */
    protected $fillable = [
        'health_care_center_id',
        'questionnaire_id',
        'question_id',
        'answer',
    ];

    /*protected $appends = ['answer_value'];*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function healthCareCenter() : BelongsTo
    {
        return $this->belongsTo(HealthCareCenter::class, 'health_care_center_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questionnaire() : BelongsTo
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

/*    public function getAnswerValueAttribute()
    {
        $answerValue = '';
        $question = $this->question()->with('options')->first();
        foreach ($question->options as $option) {
            if($this->answer == $option->key) {
                $answerValue = $option;
            }
        }
        return $answerValue;
    }*/
}
