<?php

namespace App\Console\Commands;

use App\Alerts;
use App\Services\Walmart;
use Illuminate\Console\Command;

class CheckPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walmart:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking prices from walmart.com each minute.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alerts = Alerts::all();
        foreach ($alerts as $alert) {
            $walmart = new Walmart;
            $respons = json_decode($walmart->getItems($alert->ItemID)->getBody());
            if ($respons->salePrice != $alert->price) {
                \Log::error('error');
            } else {
                \Log::info('success');
            }
        }
    }
}
