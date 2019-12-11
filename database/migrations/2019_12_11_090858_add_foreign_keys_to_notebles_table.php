<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNoteblesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notebles', function(Blueprint $table)
		{
			$table->foreign('note_id', 'notebles_fk0')->references('id')->on('notes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notebles', function(Blueprint $table)
		{
			$table->dropForeign('notebles_fk0');
		});
	}

}
