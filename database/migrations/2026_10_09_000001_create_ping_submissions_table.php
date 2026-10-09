<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ping_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('zip_code', 16);
            $table->string('traffic_source', 100)->nullable();
            $table->string('status', 32)->default('pending');
            $table->decimal('bid_amount', 10, 2)->nullable();
            $table->string('bid_id')->nullable();
            $table->string('transfer_number', 32)->nullable();
            $table->string('reject_reason')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ping_submissions');
    }
};
