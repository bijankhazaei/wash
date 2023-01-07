<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'universities';

    /**
     * @var string[]
     */
    protected $fillable = [
        'area_id',
        'name',
        'total_centers_count',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['questionnaire_count'];

    /**
     * @return mixed
     */
    public function getQuestionnaireCountAttribute()
    {
        return $this->healthCareCenters->where('q_status', '>', 1)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function healthCareCenters() : HasMany
    {
        return $this->hasMany(HealthCareCenter::class, 'university_id');
    }
}
