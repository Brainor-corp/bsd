<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToThresholdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('thresholds', function(Blueprint $table)
		{
			$table->foreign('rate_id', 'thresholds_fk0')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('thresholds', function(Blueprint $table)
		{
			$table->dropForeign('thresholds_fk0');
		});
	}

}
