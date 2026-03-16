<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Drop the default single name column
            $table->dropColumn('name');

            $table->foreignId('brand_id')
                ->nullable()
                ->after('id')
                ->constrained('brands')
                ->nullOnDelete();

            $table->foreignId('store_id')
                ->nullable()
                ->after('brand_id')
                ->constrained('stores')
                ->nullOnDelete();

            $table->string('first_name')->after('store_id');
            $table->string('last_name')->after('first_name');

            $table->string('user_code')->nullable()->after('last_name');

            $table->boolean('is_active')->default(true)->after('user_code')->index();

            $table->date('hired_at')->nullable()->after('is_active');
            $table->date('terminated_at')->nullable()->after('hired_at');
            $table->string('termination_reason')->nullable()->after('terminated_at');

            $table->boolean('is_work_stoppage')->default(false)->after('termination_reason');
            $table->date('work_stoppage_start_date')->nullable()->after('is_work_stoppage');
            $table->date('work_stoppage_end_date')->nullable()->after('work_stoppage_start_date');

            $table->date('birth_date')->nullable()->after('work_stoppage_end_date');

            $table->string('locale', 10)->default('fr')->after('birth_date');

            $table->index(['brand_id', 'store_id']);
            $table->index(['is_active', 'terminated_at']);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['store_id']);

            $table->dropIndex(['brand_id', 'store_id']);
            $table->dropIndex(['is_active', 'terminated_at']);

            $table->dropColumn([
                'brand_id',
                'store_id',
                'first_name',
                'last_name',
                'user_code',
                'is_active',
                'hired_at',
                'terminated_at',
                'termination_reason',
                'is_work_stoppage',
                'work_stoppage_start_date',
                'work_stoppage_end_date',
                'birth_date',
                'locale',
            ]);

            // Restore the default name column
            $table->string('name')->after('id');
        });
    }
};