<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRouteTariffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('route_tariffs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('route_id')->nullable()->index('route_tariffs_fk2');
			$table->integer('rate_id')->nullable()->index('route_tariffs_fk0');
			$table->integer('threshold_id')->index('route_tariffs_fk1');
			$table->decimal('price', 10);
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
		Schema::drop('route_tariffs');
	}

}
