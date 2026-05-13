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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('source');
            $table->enum('status', ['New', 'Contacted', 'Qualified', 'Proposal Sent',
                                    'Negotiation', 'Won', 'Lost'])->default('New');
            $table->string('priority');
            $table->string('expected_value');
            $table->text('notes');
            $table->foreignId('assigned_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::table('customers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
