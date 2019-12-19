<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id')->index('contacts_fk0');
			$table->string('name');
			$table->string('value');
			$table->text('description', 65535)->nullable();
			$table->string('group')->nullable();
			$table->integer('contactable_id')->nullable();
			$table->string('contactable_type')->nullable();
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
		Schema::drop('contacts');
	}

}
