<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromotionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promotions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('title', 65535)->nullable();
			$table->string('slug', 1024)->nullable();
			$table->text('text', 65535)->nullable();
			$table->date('start_at');
			$table->date('end_at');
			$table->integer('amount');
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
		Schema::drop('promotions');
	}

}
