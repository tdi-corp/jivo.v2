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
        Schema::create('jivosite_webhook_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('jivosite_webhook_id'); //
            $table->string('message'); //
            $table->integer('timestamp'); //
            $table->string('type'); // ‘visitor’ для offline_message
            $table->integer('agent_id')->nullable(); //null для offline_message
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jivosite_webhook_messages');
    }
};
