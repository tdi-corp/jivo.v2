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
        Schema::create('jivosite_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event_name'); //+
            $table->string('widget_id'); //+
            $table->string('visitor_name'); //+
            $table->string('visitor_email'); //+
            $table->string('visitor_phone'); //+
            $table->string('visitor_description'); //+
            $table->string('visitor_number'); //+
            $table->integer('chat_id'); //+
            $table->string('session_geoip_country')->nullable(); //+
            $table->string('session_geoip_region')->nullable(); //+
            $table->string('session_geoip_city')->nullable(); //+
            $table->string('session_utm_json_source')->nullable(); //+
            $table->string('session_utm_json_campaign')->nullable(); //+
            $table->string('session_utm_json_content')->nullable(); //+
            $table->string('session_utm_json_medium')->nullable(); //+
            $table->string('session_utm_json_term')->nullable(); //+
            $table->string('page_title'); //+
            $table->string('page_url'); //+
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jivosite_webhooks');
    }
};
