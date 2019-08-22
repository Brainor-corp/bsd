<?php

namespace App\Jobs;

use App\Counterparty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCounterpartiesTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
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
        $counterparties = Counterparty::whereNull('code_1c')
            ->with('type')
            ->get();
        foreach($counterparties as $counterparty) {
            dispatch(new SendCounterpartyTo1c($counterparty));
        }
    }
}
