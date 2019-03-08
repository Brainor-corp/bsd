<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBRCommentablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_r_commentables', function (Blueprint $table) {
            $table->integer('b_r_comment_id');
            $table->integer('b_r_commentable_id');
            $table->string('b_r_commentable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_r_commentables');
    }
}
