<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanalsTable extends Migration
{
    public function up()
    {
        Schema::create('canals', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Nome do canal
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('canals');
    }
}
