<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('routes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->string('name');
			$table->integer('ship_city_id')->index('routes_fk0');
			$table->integer('dest_city_id')->index('routes_fk1');
			$table->decimal('min_cost', 10, 0)->nullable();
			$table->integer('delivery_time')->nullable();
			$table->integer('base_route')->nullable()->index('routes_fk2');
			$table->integer('addition')->nullable();
			$table->integer('oversizes_id')->index('routes_fk3');
			$table->integer('wrapper_tariff');
			$table->integer('fixed_tariffs');
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
		Schema::drop('routes');
	}

}
