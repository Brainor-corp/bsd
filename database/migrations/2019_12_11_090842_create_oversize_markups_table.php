<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOversizeMarkupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oversize_markups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('oversize_id')->index('oversize_markups_fk0');
			$table->integer('rate_id')->index('oversize_markups_fk1');
			$table->decimal('value', 10, 0);
			$table->integer('threshold');
			$table->integer('markup');
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
		Schema::drop('oversize_markups');
	}

}
