<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCounterpartiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('counterparties', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id');
			$table->boolean('active')->default(0);
			$table->text('legal_form', 65535)->nullable();
			$table->text('company_name', 65535)->nullable();
			$table->text('legal_address_city', 65535)->nullable();
			$table->text('legal_address', 65535)->nullable();
			$table->text('inn', 65535)->nullable();
			$table->text('kpp', 65535)->nullable();
			$table->text('phone', 65535)->nullable();
			$table->text('name', 65535)->nullable();
			$table->text('passport_series', 65535)->nullable();
			$table->text('passport_number', 65535)->nullable();
			$table->text('addition_info', 65535)->nullable();
			$table->text('contact_person', 65535)->nullable();
			$table->text('hash_name', 65535)->nullable();
			$table->text('hash_inn', 65535)->nullable();
			$table->string('code_1c')->nullable();
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
		Schema::drop('counterparties');
	}

}
