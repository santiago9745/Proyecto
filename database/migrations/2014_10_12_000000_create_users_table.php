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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('nombre')->nullable();
            $table->string('primerApellido')->nullable();
            $table->string('segundoApellido')->nullable();
            $table->string('email')->unique();
            $table->string('rol')->nullable()->default('usuario');
            $table->string('password');
            $table->integer('estado')->default(1);
            $table->rememberToken();
            $table->timestamp('fechaCreacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fechaModificacion')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('local')->nullable();
            $table->foreign('local')->references('ID_Local')->on('locales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
