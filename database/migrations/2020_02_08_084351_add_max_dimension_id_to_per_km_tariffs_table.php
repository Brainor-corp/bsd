<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxDimensionIdToPerKmTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('per_km_tariffs', function (Blueprint $table) {
            $table->integer('max_dimension_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('per_km_tariffs', function (Blueprint $table) {
            $table->dropColumn('max_dimension_id');
        });
    }
}
