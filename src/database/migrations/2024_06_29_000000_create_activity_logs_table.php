<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('method');
            $table->string('path');
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->integer('response_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
