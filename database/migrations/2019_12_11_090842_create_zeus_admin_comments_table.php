<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id', 191)->nullable();
			$table->string('email', 191)->nullable();
			$table->string('fio', 191)->nullable();
			$table->string('ip', 191)->nullable();
			$table->text('text', 65535);
			$table->float('rating', 10, 0)->nullable();
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
		Schema::drop('zeus_admin_comments');
	}

}
