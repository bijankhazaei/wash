<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HealthCareCenter extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'health_care_centers';

    /**
     * @var string[]
     */
    protected $fillable = [
        'area_id',
        'university_id',
        'city_id',
        'latitude',
        'longitude',
        'name',
        'q_status',
        'health_care_facility_type',
        'non_hospital_facilities_type',
        'nominal_beds',
        'active_beds',
        'managerial_type',
        'urban_type',
        'men_staffs',
        'women_staffs',
        'confirm',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'type',
        'questionnaire_status',
    ];

    /**
     * @return string
     */
    public function getTypeAttribute() : string
    {
        $type = '';
        if ($this->health_care_facility_type == 1) {
            $type .= ' بیمارستان';
        }

        switch ($this->non_hospital_facilities_type) {
            case 1 :
                $type .= ' مرکز مراقبت های اولیه بهداشتی- خانه بهداشت ';
                break;
            case 2 :
                $type .= '  مرکز مراقبت های اولیه بهداشتی- خانه بهداشت سیار (خانه بهداشت عشایری) ';
                break;
            case 3 :
                $type .= '  مرکز مراقبت های اولیه بهداشتی- پایگاه سلامت ';
                break;
            case 4 :
                $type .= '  مرکز مراقبت های اولیه بهداشتی – کلینیک ها ';
                break;
            case 5 :
                $type .= '  مرکز مراقبت های بهداشتی ثانویه - مرکز خدمات جامع سلامت شهری ';
                break;
            case 6 :
                $type .= ' مرکز مراقبت های بهداشتی ثانویه - مرکز خدمات جامع سلامت روستایی  ';
                break;
        }

        if ($this->managerial_type == 2) {
            $type .= '  غیر دولتی ';
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getQuestionnaireStatusAttribute() : string
    {
        $status = '';

        switch ($this->q_status) {
            case 0 :
                $status = 'اقدامی صورت نگرفته است';
                break;
            case 1 :
                $status = 'درحال انجام';
                break;
            case 2 :
                $status = 'تکمیل شده';
                break;
            case 3 :
                $status = ' رد شده';
                break;
            case 4 :
                $status = 'تایید شده';
                break;
        }

        return $status;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class, 'health_care_center_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function questionnaire() : HasOne
    {
        return $this->hasOne(Questionnaire::class, 'health_care_center_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function university() : BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id', 'id');
    }
}
