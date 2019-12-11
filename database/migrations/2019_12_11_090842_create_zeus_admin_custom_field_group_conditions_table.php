<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCustomFieldGroupConditionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_custom_field_group_conditions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('group_id');
			$table->string('condition_type', 191)->default('in');
			$table->string('condition_parameter', 191)->default('any');
			$table->string('condition_value', 191)->default('any');
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
		Schema::drop('zeus_admin_custom_field_group_conditions');
	}

}
