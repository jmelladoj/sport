<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_cliente');
            $table->string('nombre_servicio');
            $table->string('nombre_profesional');
            $table->String('fecha_servicio');

            $table->unsignedBigInteger('cliente_id')->nullable()->default(null);
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedBigInteger('servicio_id')->nullable()->default(null);
            $table->foreign('servicio_id')->references('id')->on('servicios');

            $table->unsignedBigInteger('profesional_id')->nullable()->default(null);
            $table->foreign('profesional_id')->references('id')->on('profesionales');

            $table->unsignedBigInteger('hora_clinicas_id')->nullable()->default(null);
            $table->foreign('hora_clinicas_id')->references('id')->on('hora_clinicas');

            $table->text('observacion')->nullable()->default(null);
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
        Schema::dropIfExists('reservas');
    }
}
