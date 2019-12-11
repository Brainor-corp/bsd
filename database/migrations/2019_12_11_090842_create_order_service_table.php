<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_service', function(Blueprint $table)
		{
			$table->integer('order_id')->index('order_service_fk0');
			$table->integer('service_id')->index('order_service_fk1');
			$table->string('price');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_service');
	}

}
