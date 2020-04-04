<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertLandingCmsCategoryToZeusAdminTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category = new \Zeus\Admin\Cms\Models\ZeusAdminTerm();
        $category->title = 'Посадочная услуга';
        $category->type = 'category';
        $category->description = 'Системная категория для вывода услуг на посадочных страницах';
        $category->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Zeus\Admin\Cms\Models\ZeusAdminTerm::where([
            ['slug', 'posadochnaya-usluga'],
            ['type', 'category'],
        ])->delete();
    }
}
