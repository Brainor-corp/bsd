<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('comments_fk0');
			$table->string('email');
			$table->string('fio');
			$table->string('ip');
			$table->text('text', 65535)->nullable();
			$table->integer('rating');
			$table->boolean('visible')->nullable()->default(0);
			$table->boolean('moderate')->nullable()->default(0);
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
		Schema::drop('comments');
	}

}
