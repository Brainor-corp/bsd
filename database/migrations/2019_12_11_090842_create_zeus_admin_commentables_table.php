<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminCommentablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_commentables', function(Blueprint $table)
		{
			$table->integer('zeus_admin_comment_id');
			$table->integer('zeus_admin_commentable_id');
			$table->string('zeus_admin_commentable_type', 191);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('zeus_admin_commentables');
	}

}
