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
        Schema::table('fund_disbursals', function (Blueprint $table) {
            $table->foreignId('given_by_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_disbursals', function (Blueprint $table) {
            $table->dropForeign(['given_by_user_id']);
            $table->dropColumn('given_by_user_id');
        });
    }
};
