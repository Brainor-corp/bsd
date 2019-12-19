<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id');
			$table->text('shipping_name', 65535);
			$table->integer('cargo_type')->nullable();
			$table->string('cargo_status_id')->nullable();
			$table->string('cargo_number')->nullable();
			$table->float('total_weight', 10, 0);
			$table->float('total_volume', 10, 0);
			$table->float('actual_weight', 10, 0)->nullable();
			$table->float('actual_volume', 10, 0)->nullable();
			$table->text('cargo_comment', 65535)->nullable();
			$table->time('ship_time_from')->nullable();
			$table->time('ship_time_to')->nullable();
			$table->string('order_id')->nullable();
			$table->integer('status_id')->nullable()->index('orders_fk0');
			$table->integer('payment_status_id')->nullable();
			$table->string('payment_id')->nullable();
			$table->boolean('payment_sync_need')->default(0);
			$table->integer('ship_city_id')->nullable()->index('orders_fk1');
			$table->text('ship_city_name', 65535)->nullable();
			$table->integer('dest_city_id')->nullable()->index('orders_fk2');
			$table->text('dest_city_name', 65535)->nullable();
			$table->boolean('take_need')->nullable()->default(0);
			$table->boolean('take_in_city')->nullable()->default(0);
			$table->text('take_address', 65535)->nullable();
			$table->integer('take_distance')->nullable();
			$table->boolean('take_point')->nullable();
			$table->dateTime('take_time')->nullable();
			$table->text('take_price', 65535)->nullable();
			$table->text('take_city_name', 65535)->nullable();
			$table->integer('take_polygon_id')->nullable();
			$table->boolean('delivery_need')->nullable()->default(0);
			$table->date('estimated_delivery_date')->nullable();
			$table->boolean('delivery_in_city')->nullable()->default(0);
			$table->text('delivery_address', 65535)->nullable();
			$table->integer('delivery_distance')->nullable();
			$table->boolean('delivery_point')->nullable();
			$table->text('delivery_price', 65535)->nullable();
			$table->text('delivery_city_name', 65535)->nullable();
			$table->integer('bring_polygon_id')->nullable();
			$table->dateTime('delivery_time')->nullable();
			$table->integer('delivered_in')->nullable();
			$table->text('total_price', 65535)->nullable();
			$table->float('actual_price', 10, 0)->nullable();
			$table->text('base_price', 65535);
			$table->float('insurance', 10, 0);
			$table->text('insurance_amount', 65535);
			$table->float('discount', 10, 0)->nullable();
			$table->text('discount_amount', 65535)->nullable();
			$table->integer('user_id')->nullable()->index('orders_fk3');
			$table->text('enter_id', 65535)->nullable();
			$table->integer('sender_type_id')->nullable();
			$table->text('sender_legal_form', 65535)->nullable();
			$table->text('sender_company_name', 65535)->nullable();
			$table->text('sender_legal_address_city', 65535)->nullable();
			$table->text('sender_legal_address', 65535)->nullable();
			$table->string('sender_inn')->nullable();
			$table->string('sender_kpp')->nullable();
			$table->text('sender_name', 65535)->nullable();
			$table->string('sender_phone')->nullable();
			$table->text('sender_addition_info', 65535)->nullable();
			$table->string('sender_passport_series')->nullable();
			$table->string('sender_passport_number')->nullable();
			$table->integer('recipient_type_id')->nullable();
			$table->text('recipient_name', 65535)->nullable();
			$table->string('recipient_phone')->nullable();
			$table->text('payer_name', 65535)->nullable();
			$table->text('sender_contact_person', 65535)->nullable();
			$table->text('recipient_legal_form', 65535)->nullable();
			$table->text('recipient_company_name', 65535)->nullable();
			$table->text('recipient_legal_address_city', 65535)->nullable();
			$table->text('recipient_legal_address', 65535)->nullable();
			$table->text('recipient_contact_person', 65535)->nullable();
			$table->text('recipient_passport_series', 65535)->nullable();
			$table->text('recipient_passport_number', 65535)->nullable();
			$table->string('recipient_inn')->nullable();
			$table->string('recipient_kpp')->nullable();
			$table->text('recipient_addition_info', 65535)->nullable();
			$table->text('payer_addition_info', 65535)->nullable();
			$table->integer('payer_form_type_id')->nullable();
			$table->text('payer_passport_series', 65535)->nullable();
			$table->string('payer_passport_number')->nullable();
			$table->text('payer_legal_form', 65535)->nullable();
			$table->text('payer_company_name', 65535)->nullable();
			$table->text('payer_legal_address_city', 65535)->nullable();
			$table->text('payer_legal_address', 65535)->nullable();
			$table->text('payer_contact_person', 65535)->nullable();
			$table->text('payer_email', 65535)->nullable();
			$table->string('payer_phone')->nullable();
			$table->string('payer_inn')->nullable();
			$table->string('payer_kpp')->nullable();
			$table->integer('payer_type')->nullable();
			$table->integer('payment_type')->nullable()->index('orders_fk6');
			$table->text('order_creator', 65535)->nullable();
			$table->integer('order_creator_type')->nullable();
			$table->text('code_1c', 65535)->nullable();
			$table->integer('manager_id')->nullable()->index('orders_fk7');
			$table->integer('operator_id')->nullable()->index('orders_fk8');
			$table->dateTime('order_date')->nullable();
			$table->dateTime('order_finish_date')->nullable();
			$table->boolean('sync_need')->default(1);
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
		Schema::drop('orders');
	}

}
