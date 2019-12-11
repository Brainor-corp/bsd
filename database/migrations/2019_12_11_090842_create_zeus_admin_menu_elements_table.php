<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminMenuElementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_menu_elements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('menu_id');
			$table->string('title', 512);
			$table->string('slug', 191)->unique();
			$table->string('url', 1024)->default('/');
			$table->text('description', 65535)->nullable();
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
		Schema::drop('zeus_admin_menu_elements');
	}

}
