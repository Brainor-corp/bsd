<?php

namespace App\Http\Controllers;

use App\Event;
use App\Order;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    public function profileData() {
        return view('v1.pages.profile.profile-data.profile-data');
    }

    public function edit(Request $request) {

        $user = User:: where('id', $request->user_id)->first();
        if($user){
            if(Hash::check($request->old_password, $user->password)){
                User::where('id', $request->user_id)->update(
                    [
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'patronomic' => $request->patronomic,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]
                );
                $message = [
                    'type' => 'alert-success',
                    'data' => 'Данные успешно обновлены'
                ];
            }
            else{
                $message = [
                    'type' => 'alert-warning',
                    'data' => 'Старый пароль введен неверно'
                ];
            }
        }else{
            $message = [
                'type' => 'alert-warning',
                'data' => 'Пользователь с указанным ID не найден'
            ];
        }
        return redirect()->back()->with($message['type'], $message['data']);
    }

    public function showEventListPage(){
        $user = Auth::user();

        $events = Event::where([['user_id', $user->id], ['visible', true]])->get();
        return View::make('v1.pages.profile.profile-inner.event-list-page')->with(compact('events'));
    }

    public function showReportListPage(){
        $orders = Order::all();
        return View::make('v1.pages.profile.profile-inner.report-list-page')->with(compact('orders'));
    }

    public function actionHideEvent(Request $request){
        $event = Event::whereId($request->event_id)->firstOrFail();

        $event->visible = false;
        $event->update();
        return 'ok';
    }
}
