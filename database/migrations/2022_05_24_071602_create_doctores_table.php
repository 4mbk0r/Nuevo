<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CreateDoctoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctores', function (Blueprint $table) {
            $table->text('ci')->primary();
            //$table->foreignId('nombre')->nullable()->index();
            $table->text('nombre');
            $table->text('ap_paterno', 100)->nullable();
            $table->text('ap_materno', 100)->nullable();
            $table->text('celular', 100)->nullable();
            $table->text('especialidad', 100)->nullable();
        });

        $value = array(
            'ci' => '-1',
            'nombre' => 'No fue atendido',
            'ap_materno' => '',
            'ap_paterno' => '',
            'celular' => '',
            'especialidad' => '',
        );
        DB::table('doctores')->insert(
            $value
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctores');
    }
}
