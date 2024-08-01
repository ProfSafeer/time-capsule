<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('capsules', function (Blueprint $table) {
            $table->boolean('is_opened')->default(false)->after('opening_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('capsules', function (Blueprint $table) {
            $table->dropColumn('is_opened');
        });
    }
};
