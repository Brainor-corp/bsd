<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBRTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_r_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('_lft')->nullable();
            $table->integer('_rgt')->nullable();
            $table->integer('depth')->nullable();
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
        Schema::dropIfExists('b_r_terms');
    }
}
