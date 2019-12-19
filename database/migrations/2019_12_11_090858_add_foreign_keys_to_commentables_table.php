<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commentables', function(Blueprint $table)
		{
			$table->foreign('comment_id', 'commentables_fk0')->references('id')->on('comments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('commentables', function(Blueprint $table)
		{
			$table->dropForeign('commentables_fk0');
		});
	}

}
