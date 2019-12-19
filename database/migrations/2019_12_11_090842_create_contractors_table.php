<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractors', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id')->index('contractors_fk0');
			$table->integer('legal_form')->nullable()->index('contractors_fk1');
			$table->integer('inn')->nullable();
			$table->string('name')->nullable();
			$table->string('phone')->nullable();
			$table->string('passport_number')->nullable();
			$table->integer('discount')->nullable();
			$table->integer('balance')->nullable();
			$table->boolean('registered')->nullable()->default(0);
			$table->integer('status')->nullable()->index('contractors_fk2');
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
		Schema::drop('contractors');
	}

}
