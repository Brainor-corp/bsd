<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('uuid', 36)->nullable();
			$table->string('mime', 191);
			$table->string('extension', 191);
			$table->string('url', 191);
			$table->string('base_url', 191);
			$table->string('path', 191);
			$table->float('size', 10, 0);
			$table->string('title', 191)->nullable();
			$table->string('alt', 191)->nullable();
			$table->text('description', 65535)->nullable();
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
		Schema::drop('zeus_admin_files');
	}

}
