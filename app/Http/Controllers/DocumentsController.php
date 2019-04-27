<?php

namespace App\Http\Controllers;

use App\City;
use App\CustomTag;
use App\Review;
use App\ReviewFile;
use Bradmin\Cms\Models\BRTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DocumentsController extends Controller
{
    public function showDocuments() {
        $documentRootType = BRTag::where('slug', 'dokumenty-i-sertifikaty')->firstOrFail();
        $documentTypes = CustomTag::with('files')->descendantsOf($documentRootType);

        return View::make('v1.pages.about.documents-page')->with(compact('documentTypes'));
    }
}
