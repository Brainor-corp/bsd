<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCustomFieldGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_custom_field_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('slug', 191);
			$table->text('description', 65535)->nullable();
			$table->string('position', 191);
			$table->string('order', 191)->default('99.99');
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
		Schema::drop('zeus_admin_custom_field_groups');
	}

}
