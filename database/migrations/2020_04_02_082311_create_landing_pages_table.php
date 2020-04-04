<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('key_words')->nullable();
            $table->string('url');
            $table->string('template')->nullable();
            $table->integer('route_id');
            $table->text('text_1')->nullable();
            $table->text('text_2')->nullable();
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('routes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropIfExists();
        });
    }
}
