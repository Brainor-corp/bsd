<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizesTypesInForwardThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forward_thresholds', function (Blueprint $table) {
            $table->decimal('length', 10, 3)->change();
            $table->decimal('width', 10, 3)->change();
            $table->decimal('height', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forward_thresholds', function (Blueprint $table) {
            //
        });
    }
}
