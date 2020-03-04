<?php

namespace App\Jobs;

use App\PendingFile;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearPendingFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Удалим файлы в состоянии ожидания, которые загружены более суток назад
        $pendingFiles = PendingFile::where('created_at', '<', Carbon::now()->subDay())->get();
        foreach($pendingFiles as $file) {
            if(file_exists($file->path)) {
                unlink($file->path);
            }

            $file->delete();
        }

        // Удалим файлы, которых нет ни в таблице ожидающих файлов, ни в таблице заявок
        foreach(Storage::disk('available_public')->files('files/order-files') as $file) {
            if(
                !DB::table('pending_files')->where('path', public_path($file))->exists()
                && !DB::table('orders')->where('take_driving_directions_file', $file)->exists()
                && !DB::table('orders')->where('delivery_driving_directions_file', $file)->exists()
            ) {
                unlink(public_path($file));
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
