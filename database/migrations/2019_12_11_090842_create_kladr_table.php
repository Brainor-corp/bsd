<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKladrTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kladr', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name')->nullable();
			$table->string('full_name')->nullable();
			$table->string('lower_name');
			$table->string('socr')->nullable();
			$table->string('code');
			$table->integer('popularity');
			$table->string('country_code');
			$table->integer('region_code');
			$table->string('district_code');
			$table->string('city_code');
			$table->string('town_code');
			$table->integer('actuality');
			$table->boolean('enabled')->nullable()->default(0);
			$table->integer('terminal_id')->nullable();
			$table->integer('distance')->nullable();
			$table->boolean('is_terminal_city')->nullable()->default(0);
            $table->point('geo_point')->nullable();
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
		Schema::drop('kladr');
	}

}
