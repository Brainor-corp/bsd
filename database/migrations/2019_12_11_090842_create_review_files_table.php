<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('review_files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('review_id');
			$table->string('name')->nullable();
			$table->string('url');
			$table->string('base_url');
			$table->string('mime');
			$table->bigInteger('size');
			$table->string('extension');
			$table->string('alt')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('path');
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
		Schema::drop('review_files');
	}

}
