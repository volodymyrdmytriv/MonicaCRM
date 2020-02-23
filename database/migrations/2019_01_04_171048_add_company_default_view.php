<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyDefaultView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wc_companies', function (Blueprint $table) {
            $table->string('active_tab')->default('notes')->after('satisfaction_rate');
        });
    }

}
