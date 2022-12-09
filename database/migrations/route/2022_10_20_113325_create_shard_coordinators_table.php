<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shard_coordinators', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('subdomain', 255)->unique();
            $table->uuid('uuid')->unique();
            $table->string('dbname', 255)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shard_coordinators');
    }
};
