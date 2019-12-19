<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForwardThresholdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forward_thresholds', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->string('name');
			$table->float('weight', 10, 0);
			$table->float('volume', 10, 0);
			$table->integer('units');
			$table->integer('threshold_group_id')->nullable()->index('forward_thresholds_fk0');
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
		Schema::drop('forward_thresholds');
	}

}
