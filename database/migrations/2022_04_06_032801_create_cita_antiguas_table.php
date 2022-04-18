<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitaAntiguasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cita_antiguas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->time('hora');
            $table->text('paciente_ci');
            $table->foreign('paciente_ci')->references('ci')->on('personas_antiguos');
            $table->text('estado');
            $table->text('lugar');
            $table->text('se_presento');
            $table->text('observacion');
            $table->text('equipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cita_antiguas');
    }
}
