<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContractorUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contractor_user', function(Blueprint $table)
		{
			$table->foreign('contractor_id', 'contractor_user_fk0')->references('id')->on('contractors')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'contractor_user_fk1')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contractor_user', function(Blueprint $table)
		{
			$table->dropForeign('contractor_user_fk0');
			$table->dropForeign('contractor_user_fk1');
		});
	}

}
