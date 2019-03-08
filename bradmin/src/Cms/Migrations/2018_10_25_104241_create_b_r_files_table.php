<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBRFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_r_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mime');
            $table->string('extension');
            $table->string('url');
            $table->string('base_url');
            $table->string('path');
            $table->double('size');
            $table->string('title')->nullable();
            $table->string('alt')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('b_r_files');
    }
}
