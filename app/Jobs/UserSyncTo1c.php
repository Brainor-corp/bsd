<?php

namespace App\Jobs;

use App\Http\Helpers\Api1CHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserSyncTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $notSynchronizedUser = $this->user;

        $response1c = Api1CHelper::post(
            'new_user',
            [
                'email' => $notSynchronizedUser->email,
                'tel' => intval($notSynchronizedUser->phone) // Для Api важно, чтобы номер был цифрой
            ],
            false,
            5
        );

        if(
            $response1c['status'] == 200
            && !empty($response1c['response']['id'])
            && !empty($response1c['response']['status'])
            && $response1c['response']['status'] === 'success'
            && $response1c['response']['id'] !== 'not found'
        ) {
            DB::table('users')->where('id', $notSynchronizedUser->id)->update([
                'guid' => $response1c['response']['id'],
                'sync_need' => false
            ]);
        }
    }
}
