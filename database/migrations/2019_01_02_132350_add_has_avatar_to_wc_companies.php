<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasAvatarToWcCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wc_companies', function (Blueprint $table) {
            $table->boolean('has_avatar')->default(0)->after('description');
        });
    }

}
