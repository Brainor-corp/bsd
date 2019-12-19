<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOversizesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oversizes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->float('length', 10, 0);
			$table->float('width', 10, 0);
			$table->float('height', 10, 0);
			$table->float('volume', 10, 0);
			$table->float('weight', 10, 0);
			$table->float('ratio', 10, 0);
			$table->integer('company_id')->nullable();
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
		Schema::drop('oversizes');
	}

}
