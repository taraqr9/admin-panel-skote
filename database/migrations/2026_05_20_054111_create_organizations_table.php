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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->nullable(); // Old column: leave_manager_code

            $table->string('name')->nullable();
            $table->unsignedTinyInteger('fiscal_start_month')->nullable();
            $table->unsignedTinyInteger('fiscal_end_month')->nullable();
            $table->year('fiscal_year')->nullable();

            $table->boolean('is_active')->nullable()->default(true); // Old column: status. Old status: 1 = active, 0 = deleted

            $table->string('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
