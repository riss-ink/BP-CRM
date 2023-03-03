<?php

namespace App\Jobs;

use App\Client;
use App\Enums\ClientStatus;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClientCheck implements ShouldQueue
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
        $clients = Client::where([['status', '=' ,ClientStatus::Canceled], ['canceled_at', '>=', Carbon::now()->subDays(90)]])->get();
        foreach ($clients as $client){
            $client->delete();
        }
    }
}
