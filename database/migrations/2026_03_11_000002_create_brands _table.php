<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            $table->foreignId('theme_id')
                ->nullable()
                ->constrained('themes')
                ->nullOnDelete();

            $table->string('name');
            $table->string('tag')->unique();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            $table->json('design_config')->nullable();

            $table->json('links')->nullable();

            $table->string('sms_phone_number', 20)->nullable();
            $table->string('email_from_address')->nullable();
            $table->string('email_from_name')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};