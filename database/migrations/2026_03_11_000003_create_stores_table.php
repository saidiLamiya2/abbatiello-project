<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnDelete();

            $table->foreignId('core_store_id')
                ->nullable()
                ->constrained('stores')
                ->nullOnDelete();

            $table->unsignedBigInteger('store_employee_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('franchise_number')->nullable();

            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('logo')->nullable();

            $table->string('address')->nullable();
            $table->string('city');
            $table->string('province', 10)->nullable();
            $table->string('postal_code', 10)->nullable();

            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            $table->boolean('is_active')->default(true)->index();

            $table->enum('project_type', ['Nouveau', 'Corpo', 'Reprise', 'Vente'])->nullable();

            $table->date('start_date')->nullable();
            $table->date('expected_opening_date')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index(['brand_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};