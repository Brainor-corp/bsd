<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commentables', function(Blueprint $table)
		{
			$table->integer('comment_id')->index('commentables_fk0');
			$table->integer('commentable_id');
			$table->string('commentable_type');
			$table->string('option_1')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('commentables');
	}

}
