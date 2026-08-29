<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('person', function (Blueprint $table) {
            $table->dropColumn('person_type_id');
        });
        
        Schema::table('person', function (Blueprint $table) {
            $table->uuid('person_type_id')->nullable()->default(null);
            $table->foreign('person_type_id')
                ->references('id')
                ->on('person_types')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person', function (Blueprint $table) {
            $table->dropColumn('person_type_id');
        });
    }
};
