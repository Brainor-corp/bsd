<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerKmTariffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('per_km_tariffs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('tariff_zone_id')->index('per_km_tariffs_fk0');
			$table->integer('forward_threshold_id')->index('per_km_tariffs_fk1');
			$table->integer('tariff')->nullable();
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
		Schema::drop('per_km_tariffs');
	}

}
