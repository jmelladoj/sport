<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string('run')->unique();
            $table->string('nombre');
            $table->string('direccion')->nullable()->default(null);
            $table->string('correo')->nullable()->default(null);
            $table->integer('telefono');
            $table->string('fecha_nacimiento')->nullable()->default(null);
            $table->string('observacion')->nullable()->default(null);

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
        Schema::dropIfExists('clientes');
    }
}
