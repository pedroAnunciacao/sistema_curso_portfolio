<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {

            $table->unsignedBigInteger('client_id')->after('id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->unsignedBigInteger('student_id')->nullable()->after('client_id');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('set null');

            $table->unsignedBigInteger('teacher_id')->nullable()->after('student_id');
            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['student_id']);
            $table->dropForeign(['teacher_id']);

            $table->dropColumn(['client_id', 'student_id', 'teacher_id']);
        });
    }
};
