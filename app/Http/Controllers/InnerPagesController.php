<?php

namespace App\Http\Controllers;

use App\CustomTag;
use Bradmin\Cms\Models\BRTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class InnerPagesController extends Controller {
    public function showDocuments() {
        $documentRootType = BRTag::where('slug', 'dokumenty-i-sertifikaty')->firstOrFail();
        $documentTypes = CustomTag::with('files')->descendantsOf($documentRootType);

        return View::make('v1.pages.about.documents-page')->with(compact('documentTypes'));
    }
}
