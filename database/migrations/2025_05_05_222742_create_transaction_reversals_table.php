<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('transaction_reversals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('original_transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->foreignId('reversed_by')->constrained('users')->onDelete('cascade');
        $table->text('reason')->nullable();
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_reversals');
    }
};
