<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobCoworkerPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wc_job_coworker', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');
            $table->integer('coworker_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function drop()
    {
        Schema::table('wc_job_coworker', function (Blueprint $table) {
            //
        });
    }
}
