<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('guid')->nullable()->unique('guid_unique');
			$table->boolean('sync_need')->default(1);
			$table->string('email')->unique('email_unique');
			$table->string('name');
			$table->string('surname')->nullable();
			$table->string('patronomic')->nullable();
			$table->string('password');
			$table->boolean('need_password_reset')->nullable()->default(0);
			$table->string('phone')->nullable();
			$table->integer('phone_verification_code')->nullable();
			$table->dateTime('code_send_at')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->boolean('verified')->nullable()->default(0);
			$table->dateTime('email_verified_at')->nullable();
			$table->string('verification_token')->nullable();
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
		Schema::drop('users');
	}

}
