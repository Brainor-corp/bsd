<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCustomFieldDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_custom_field_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('value', 65535);
			$table->integer('field_id');
			$table->text('description', 65535)->nullable();
			$table->integer('customable_id');
			$table->string('customable_type', 191);
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
		Schema::drop('zeus_admin_custom_field_data');
	}

}
