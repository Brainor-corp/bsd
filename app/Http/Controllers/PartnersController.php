<?php

namespace App\Http\Controllers;

use App\Support;
use App\Type;
use Bradmin\Cms\Helpers\CMSHelper;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    public function showPartnersPage() {
        $args = [
            'tags' => ['partner'],
            'type' => 'post',
        ];
        $partners = CMSHelper::getQueryBuilder($args)
            ->where('status', 'published')
            ->get();
        return view('v1.pages.partners.partners-page')->with(compact('partners'));
    }

    public function sendPartnersFeedback(Request $request){
        $type = Type::where('slug', 'zayavka-partnera')->firstOrFail();

        $support = new Support();
        $support->fio = $request->get('fio');
        $support->company_name = $request->get('company_name');
        $support->phone = $request->get('phone');
        $support->subject_id = $type->id;
        $support->text = $request->get('text');

        $support->saveOrFail();

        return redirect()->back()->withSuccess('Заявка успешно отправлена.');
    }
}
