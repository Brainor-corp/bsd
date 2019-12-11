<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsPhonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('password_resets_phones', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('phone', 191)->unique('phone');
			$table->integer('code');
			$table->string('token', 191)->unique('token');
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
		Schema::drop('password_resets_phones');
	}

}
