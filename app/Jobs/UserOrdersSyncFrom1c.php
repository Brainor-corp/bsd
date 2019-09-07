<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserOrdersSyncFrom1c implements ShouldQueue
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
        $user = $this->user;

        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'orders',
            [
                'user_id' => $user->guid,
            ]
        );

        if($response1c['status'] == 200 && !empty($response1c['response']['documents'])) {
            foreach($response1c['response']['documents'] as $document) {
                if(!Order::where('code_1c', $document['id'])->exists()) {
                    dispatch(new UserOrderSyncFrom1c($user, $document));
                }
            }
        }
    }
}
