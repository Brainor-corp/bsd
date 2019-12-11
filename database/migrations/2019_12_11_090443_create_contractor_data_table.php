<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractorDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractor_data', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->string('slug')->unique('slug');
			$table->string('value');
			$table->integer('contractor_id')->index('contractor_data_fk0');
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
		Schema::drop('contractor_data');
	}

}
