<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZeusAdminPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zeus_admin_posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 191);
			$table->string('title', 191);
			$table->string('slug', 191)->unique();
			$table->text('description', 65535)->nullable();
			$table->text('content', 65535)->nullable();
			$table->string('status', 191);
			$table->string('url', 191);
			$table->integer('parent_id')->nullable();
			$table->integer('_lft')->nullable();
			$table->integer('_rgt')->nullable();
			$table->integer('depth')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('template', 191)->nullable();
			$table->string('thumb', 191)->nullable();
			$table->boolean('comment_on');
			$table->dateTime('published_at');
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
		Schema::drop('zeus_admin_posts');
	}

}
