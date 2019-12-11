<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 191);
			$table->string('slug', 191)->unique();
			$table->string('class', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('order')->default(0);
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
		Schema::drop('zeus_admin_menus');
	}

}
