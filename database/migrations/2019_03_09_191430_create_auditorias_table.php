<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('auditorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('audio1');
            $table->string('audio2')->nullable();
            $table->string('audio3')->nullable();
            $table->string('observacion')->nullable();
            $table->string('adherentes')->nullable();
            $table->bigInteger('id_venta');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('auditorias');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
