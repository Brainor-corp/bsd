<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContractorDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contractor_data', function(Blueprint $table)
		{
			$table->foreign('contractor_id', 'contractor_data_fk0')->references('id')->on('contractors')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contractor_data', function(Blueprint $table)
		{
			$table->dropForeign('contractor_data_fk0');
		});
	}

}
