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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Person who spent
            $table->string('category')->default('General'); // Office, Transport, Food, Event, Utilities, etc.
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('title');
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
        Schema::dropIfExists('expenses');
    }
};
