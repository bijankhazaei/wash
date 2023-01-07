<?php

namespace App\Imports;

use App\Models\HealthCareCenter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HealthCareCenterImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new HealthCareCenter([
            'area_id'                      => $row['area_id'],
            'university_id'                => $row['university_id'],
            'city_id'                      => $row['city_id'],
            'latitude'                     => is_float($row['latitude']) && floatval($row['latitude']) < 100 ? $row['latitude'] : 00.000,
            'longitude'                    => is_float($row['longitude']) && floatval($row['longitude']) < 100 ? $row['longitude'] : 00.000,
            'name'                         => $row['name'],
            'health_care_facility_type'    => $row['health_care_facility_type'],
            'non_hospital_facilities_type' => $row['non_hospital_facilities_type'],
            'managerial_type'              => $row['managerial_type'],
            'urban_type'                   => $row['urban_type'],
        ]);
    }
}
