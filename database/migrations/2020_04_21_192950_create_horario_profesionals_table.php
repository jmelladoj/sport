<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioProfesionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_profesionales', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('dia');
            $table->string('hora');
            $table->boolean('trabaja')->default(false);

            $table->unsignedBigInteger('profesional_id')->nullable();
            $table->foreign('profesional_id')->references('id')->on('profesionales');

            $table->unsignedBigInteger('hora_clinicas_id')->nullable();
            $table->foreign('hora_clinicas_id')->references('id')->on('hora_clinicas');

            $table->softDeletes();
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
        Schema::dropIfExists('horario_profesionals');
    }
}
