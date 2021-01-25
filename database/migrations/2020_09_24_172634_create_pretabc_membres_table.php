<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePretabcMembresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pretabc_membres', function (Blueprint $table) {
            $table->increments('id');
            $table->text('flinksLoginId');
            $table->string('prenom');
            $table->string('nom');
            $table->string('sexe');
            $table->string('nas');
            $table->date('date_naissance');
            $table->string('telephone');
            $table->string('telephone_compagnie');

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
        Schema::dropIfExists('pretabc_membres');
    }
}
