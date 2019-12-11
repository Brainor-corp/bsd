<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('order_id')->index('order_items_fk0');
			$table->float('length', 10, 0)->nullable();
			$table->float('width', 10, 0)->nullable();
			$table->float('height', 10, 0)->nullable();
			$table->float('volume', 10, 0)->nullable();
			$table->float('weight', 10, 0);
			$table->integer('quantity');
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
		Schema::drop('order_items');
	}

}
