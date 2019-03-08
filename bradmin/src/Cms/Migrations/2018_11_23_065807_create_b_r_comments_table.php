<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBRCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_r_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('fio')->nullable();
            $table->string('ip')->nullable();
            $table->text('text');
            $table->double('rating')->nullable();
            $table->boolean('visible');
            $table->boolean('moderate');
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
        Schema::dropIfExists('b_r_comments');
    }
}
