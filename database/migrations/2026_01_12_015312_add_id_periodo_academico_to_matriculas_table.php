<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->unsignedInteger('id_periodo_academico')->nullable()->after('id_matricula');
            $table->index('id_periodo_academico');
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropIndex(['id_periodo_academico']);
            $table->dropColumn('id_periodo_academico');
        });
    }
};