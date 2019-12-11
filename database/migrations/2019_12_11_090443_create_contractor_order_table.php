<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractorOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractor_order', function(Blueprint $table)
		{
			$table->integer('contractor_id')->index('contractor_order_fk0');
			$table->integer('order_id')->index('contractor_order_fk1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contractor_order');
	}

}
