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
    Schema::table('clients', function (Blueprint $table) {
        $table->string('celular')->nullable();
        $table->string('bairro')->nullable();
        $table->string('estado')->nullable();
    });
}

public function down()
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropColumn(['celular', 'bairro', 'estado']);
    });
}
};

