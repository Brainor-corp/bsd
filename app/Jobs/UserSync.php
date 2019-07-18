<?php

namespace App\Jobs;

use App\Http\Helpers\Api1CHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        try {
            $response1c = Api1CHelper::post(
                'new_user',
                [
                    'email' => $notSynchronizedUser->email,
                    'tel' => intval($notSynchronizedUser->phone) // Для Api важно, чтобы номер был цифрой
                ]
            );

            if($response1c['status'] == 200 && !empty($response1c['response']['id'] && $response1c['response']['id'] !== 'not found')) {
                $notSynchronizedUser->guid = $response1c['response']['id'];
                $notSynchronizedUser->sync_need = false;
                $notSynchronizedUser->update();
            } else {
                // Тригерим ошибку, чтобы job с неудачным пользователем упал в failed jobs
                throw new \Exception("Пользователь " . $notSynchronizedUser->id . " не обработан.");
            }
        } catch (\Exception $exception) {
            // Тригерим ошибку, чтобы job с неудачным пользователем упал в failed jobs
            throw new \Exception($exception->getMessage());
        }
    }
}
