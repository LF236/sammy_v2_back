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
		Schema::create('person', function (Blueprint $table) {
			$table->id();
			$table->string('fullName')->nullable();
			$table->string('firstName')->nullable();
			$table->string('lastName')->nullable();
			$table->string('rfc')->nullable();
			$table->string('profesional_id')->nullable();
			$table->date('birthDate')->nullable();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		//
		Schema::dropIfExists('person');
    }
};
