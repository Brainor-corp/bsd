<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminTermablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_termables', function(Blueprint $table)
		{
			$table->integer('zeus_admin_term_id');
			$table->integer('zeus_admin_termable_id');
			$table->string('zeus_admin_termable_type', 191);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('zeus_admin_termables');
	}

}
