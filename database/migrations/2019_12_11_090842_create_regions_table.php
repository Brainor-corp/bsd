<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('regions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('code')->index('regions_code');
			$table->string('name');
			$table->integer('fixed_tariff');
			$table->integer('dist_tariff');
			$table->integer('inside_tariff');
			$table->integer('dest_city_id')->nullable()->index('regions_fk0');
			$table->integer('threshold_group_id')->nullable()->index('regions_fk1');
			$table->integer('tariff_zone_id')->index('regions_fk2');
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
		Schema::drop('regions');
	}

}
