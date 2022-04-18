<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_final');
            $table->text('ci',20);
            $table->text('tipo_cita',20);
            $table->text('se_presento',20);
            $table->text('observacion');
            $table->integer('equipo');
            $table->text('lugar',20);
            $table->primary(['fecha', 'hora_inicio', 'equipo']);
                        $table->foreign('ci')->references('ci')->on('persona_citas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
