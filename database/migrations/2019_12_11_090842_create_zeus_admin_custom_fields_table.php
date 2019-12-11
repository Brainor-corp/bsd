<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCustomFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_custom_fields', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('slug', 191);
			$table->string('type', 191);
			$table->integer('group_id');
			$table->text('description', 65535)->nullable();
			$table->string('value', 191)->nullable();
			$table->string('placeholder', 191)->nullable();
			$table->text('html', 65535)->nullable();
			$table->text('options', 65535)->nullable();
			$table->boolean('required')->default(0);
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
		Schema::drop('zeus_admin_custom_fields');
	}

}
