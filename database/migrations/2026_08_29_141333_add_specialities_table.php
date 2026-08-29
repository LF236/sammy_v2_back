<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('especialities_cat');
        Schema::create('specialities_cat', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->nullable(false);
            $table->string('name')->nullable(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialities_cat');
    }
};
