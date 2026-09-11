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
        Schema::create('fund_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contributor_name');
            $table->decimal('amount', 15, 2);
            $table->date('deposit_date');
            $table->string('payment_method')->default('Cash'); // Cash, Bank, bKash, Nagad, etc.
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->string('document_path')->nullable(); // FTP path
            $table->string('document_url')->nullable(); // Public URL
            $table->string('document_filename')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_deposits');
    }
};
