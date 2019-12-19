<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForwardingReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forwarding_receipts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('code_1c', 65535);
			$table->string('number');
			$table->integer('cargo_status_id')->nullable();
			$table->date('order_date')->nullable();
			$table->integer('packages_count');
			$table->float('volume', 10, 0);
			$table->float('weight', 10, 0);
			$table->string('ship_city');
			$table->string('dest_city');
			$table->text('sender_name', 65535);
			$table->text('recipient_name', 65535);
			$table->integer('user_id');
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
		Schema::drop('forwarding_receipts');
	}

}
