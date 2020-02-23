<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wc_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('company_id');
            $table->string('title');
            $table->string('description');
            $table->dateTime('start_date');
            $table->boolean('completed')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->softDeletes();
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
        Schema::drop('wc_jobs', function (Blueprint $table) {
            //
        });
    }
}
