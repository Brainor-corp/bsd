<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToInsideForwardingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inside_forwarding', function (Blueprint $table) {
            $table->string('loading_unloading_minutes')->nullable();
            $table->string('car_overtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inside_forwarding', function (Blueprint $table) {
            $table->dropColumn('loading_unloading_minutes');
            $table->dropColumn('car_overtime');
        });
    }
}
