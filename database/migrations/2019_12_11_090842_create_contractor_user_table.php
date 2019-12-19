<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractorUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractor_user', function(Blueprint $table)
		{
			$table->integer('contractor_id')->index('contractor_user_fk0');
			$table->integer('user_id')->index('contractor_user_fk1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contractor_user');
	}

}
