<?php

namespace App\Http\Controllers;

use App\City;
use App\ContactEmail;
use App\CustomTag;
use App\Jobs\SendReviewLeftMailToAdmin;
use App\Review;
use App\ReviewFile;
use App\Rules\GoogleReCaptchaV2;
use Bradmin\Cms\Models\BRTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ReviewsController extends Controller {

    public function showReviews() {
        $reviews = Review::where('moderate', true)->with('file')->get();
        $cities = City::all();
        return View::make('v1.pages.about.reviews-page')->with(compact('reviews', 'cities'));
    }

    public function saveReview(Request $request) {
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()],
            'author' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|exists:cities,id',
            'email' => 'nullable|max:255|email',
            'text' => 'nullable|max:500',
            'review-file' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $review = new Review();

        $review->author = $request->get('author');
        $review->phone = $request->get('phone');
        $review->email = $request->get('email');
        $review->city_id = $request->get('city');
        $review->text = $request->get('text');

        $review->save();

        $uploadFile = $request->file('review-file');

        if(isset($uploadFile)) {
            $file = new ReviewFile();
            $storagePath = Storage::disk('available_public')->put('files/review-files', $uploadFile);

            $file->name = $uploadFile->getClientOriginalName();
            $file->mime = $uploadFile->getMimeType();
            $file->extension = $uploadFile->getClientOriginalExtension();
            $file->url = url($storagePath);
            $file->path = Storage::disk('available_public')->path($storagePath);
            $file->base_url = $storagePath;
            $file->size = $uploadFile->getSize();

            $review->file()->save($file);
        }

        foreach(ContactEmail::where('active', true)->get() as $email) {
            SendReviewLeftMailToAdmin::dispatch($email->email, $review);
        }

        return redirect()->back()->withSuccess('success');
    }
}
