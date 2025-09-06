<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
    }
};
