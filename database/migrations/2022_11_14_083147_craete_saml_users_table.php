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
    public function up()
    {
        Schema::create('saml_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->integer('tenant_id');
            $table->string('remote_id')->comment('The unique id provided by IDP');
            $table->json('attributes')->nullable();
            $table->timestamps();
            $table->index(['remote_id', 'tenant_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saml_users');
    }
};
