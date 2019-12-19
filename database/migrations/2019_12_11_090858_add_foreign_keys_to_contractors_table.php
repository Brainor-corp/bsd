<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContractorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contractors', function(Blueprint $table)
		{
			$table->foreign('type_id', 'contractors_fk0')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('legal_form', 'contractors_fk1')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status', 'contractors_fk2')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contractors', function(Blueprint $table)
		{
			$table->dropForeign('contractors_fk0');
			$table->dropForeign('contractors_fk1');
			$table->dropForeign('contractors_fk2');
		});
	}

}
