<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cities', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('kladr_id')->nullable()->index('cities_fk0');
			$table->string('name');
			$table->string('slug');
			$table->boolean('is_ship')->nullable()->default(0);
			$table->boolean('is_filial')->nullable()->default(0);
			$table->integer('message')->nullable();
			$table->boolean('doorstep')->nullable()->default(0);
			$table->integer('tariff_zone_id');
			$table->integer('threshold_group_id');
			$table->boolean('is_popular')->default(0);
			$table->integer('closest_terminal_id')->nullable();
			$table->boolean('update_closest_terminal')->default(1);
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
		Schema::drop('cities');
	}

}
