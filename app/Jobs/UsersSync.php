<?php

namespace App\Jobs;

use App\Http\Helpers\Api1CHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UsersSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notSynchronizedUsers = User::where('sync_need', true)->get();
        foreach($notSynchronizedUsers as $notSynchronizedUser) {
            $response1c = Api1CHelper::post(
                'new_user',
                [
                    'email' => $notSynchronizedUser->email,
                    'tel' => intval($notSynchronizedUser->phone) // Для Api важно, чтобы номер был цифрой
                ]
            );

            if($response1c['status'] == 200 && !empty($response1c['response']['id'])) {
                $notSynchronizedUser->guid = $response1c['response']['id'];
                $notSynchronizedUser->sync_need = false;
                $notSynchronizedUser->update();
            }
        }
    }
}
