<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWcCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wc_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->string('avatar_file_name')->nullable();
            $table->string('avatar_location');
            $table->dateTime('work_start_date');
            $table->dateTime('work_end_date')->nullable();
            $table->string('position');
            $table->decimal('yearly_income');
            $table->integer('satisfaction_rate');
            
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
        Schema::dropIfExists('wk_companies');
    }
}
