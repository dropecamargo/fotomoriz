<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('framework')->create('modulo1', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name')->unique()->nullable();
            $table->string('display_name')->nullable();

            $table->integer('nivel1')->default(0);
            $table->integer('nivel2')->default(0);
            $table->integer('nivel3')->default(0);
            $table->integer('nivel4')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('framework')->dropIfExists('modulo1');
    }
}
