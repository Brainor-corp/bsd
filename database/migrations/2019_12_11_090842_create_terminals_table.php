<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTerminalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terminals', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('city_id')->index('terminals_fk0');
			$table->integer('region_code')->nullable()->index('terminals_fk1');
			$table->string('name');
			$table->string('address', 2048);
			$table->string('phone', 512)->nullable();
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
		Schema::drop('terminals');
	}

}
