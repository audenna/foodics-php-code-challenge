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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->double('available_stock_in_gram')->default(0)->comment('This would be saved in Grams');
            $table->double('threshold_qty')->default(0)->comment('This is the minimum quantity that would trigger an email to the Merchant');
            $table->boolean('is_email_sent')->default(false)->comment('This updates once an email has been sent');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
