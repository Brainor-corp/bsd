<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePolygonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('polygons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->text('coordinates', 65535);
			$table->decimal('price', 10, 0);
			$table->integer('city_id');
			$table->integer('priority')->default(1);
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
		Schema::drop('polygons');
	}

}
