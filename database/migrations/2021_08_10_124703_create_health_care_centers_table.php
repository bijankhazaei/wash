<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthCareCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_care_centers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->float('latitude', '12', '8')->nullable();
            $table->float('longitude', '12', '8')->nullable();
            $table->string('name')->nullable();
            $table->integer('health_care_facility_type')->nullable();
            $table->integer('non_hospital_facilities_type')->nullable();
            $table->integer('nominal_beds')->nullable();
            $table->integer('active_beds')->nullable();
            $table->integer('managerial_type')->nullable();
            $table->integer('urban_type')->nullable();
            $table->integer('men_staffs')->nullable();
            $table->integer('women_staffs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_care_centers');
    }
}
