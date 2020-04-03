<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertLandingServicesToZeusAdminPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $servicesTitles = [
            'Оформление документов',
            'Страхование грузов',
            'Упаковка грузов',
            'Сборные грузы',
            'Опытные грузчики',
            'Срочная доставка',
            'Отслеживание груза'
        ];
        $category = \Zeus\Admin\Cms\Models\ZeusAdminTerm::where([
            ['type', 'category'],
            ['slug', 'posadochnaya-usluga']
        ])->first();

        foreach($servicesTitles as $servicesTitle) {
            $post = new \Zeus\Admin\Cms\Models\ZeusAdminPost();
            $post->title = $servicesTitle;
            $post->type = 'post';
            $post->status = 'published';
            $post->content = "<p>$servicesTitle</p>";
            $post->published_at = \Carbon\Carbon::now();
            $post->save();

            $post->url = $post->default_url;
            $post->save();

            $post->categories()->attach($category);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Zeus\Admin\Cms\Models\ZeusAdminPost::whereHas('categories', function ($categoryQ) {
            return $categoryQ->where([
                ['type', 'category'],
                ['slug', 'posadochnaya-usluga']
            ]);
        })->delete();
    }
}
