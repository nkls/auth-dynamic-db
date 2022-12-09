<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_hashes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('hash', 40)->unique();
            $table->string('status', 40);
            $table->date('expires');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_hashes');
    }
};
