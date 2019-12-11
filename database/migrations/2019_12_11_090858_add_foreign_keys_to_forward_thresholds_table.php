<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToForwardThresholdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forward_thresholds', function(Blueprint $table)
		{
			$table->foreign('threshold_group_id', 'forward_thresholds_fk0')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forward_thresholds', function(Blueprint $table)
		{
			$table->dropForeign('forward_thresholds_fk0');
		});
	}

}
