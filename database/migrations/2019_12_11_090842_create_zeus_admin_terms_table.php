<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_terms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 191);
			$table->string('title', 191);
			$table->string('slug', 191);
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
		Schema::drop('zeus_admin_terms');
	}

}
