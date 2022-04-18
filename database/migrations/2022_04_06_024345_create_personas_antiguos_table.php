<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasAntiguosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas_antiguos', function (Blueprint $table) {
            $table->text('ci')->primary();
            //$table->foreignId('nombre')->nullable()->index();
            $table->text('nombre')->nullable();
            $table->text('celular', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas_antiguos');
    }
}
