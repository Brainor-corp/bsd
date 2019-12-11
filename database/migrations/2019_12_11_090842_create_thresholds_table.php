<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThresholdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thresholds', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('old_id')->nullable();
			$table->integer('rate_id')->index('thresholds_fk0');
			$table->decimal('value', 10);
			$table->integer('company_id')->nullable();
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
		Schema::drop('thresholds');
	}

}
