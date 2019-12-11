<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOversizeMarkupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('oversize_markups', function(Blueprint $table)
		{
			$table->foreign('oversize_id', 'oversize_markups_fk0')->references('id')->on('oversizes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('rate_id', 'oversize_markups_fk1')->references('id')->on('types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('oversize_markups', function(Blueprint $table)
		{
			$table->dropForeign('oversize_markups_fk0');
			$table->dropForeign('oversize_markups_fk1');
		});
	}

}
