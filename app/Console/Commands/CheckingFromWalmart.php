<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Services\Contractors\CronJobUpdateDataInterface;

class CheckingFromWalmart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walmart:walmart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare database with Walmart.';

    /**
     * @var CronJobUpdateDataInterface
     */
    protected $cronJob;

    /**
     * Create a new command instance.
     * @param CronJobUpdateDataInterface $cronJobUpdateDataInterface
     * @return void
     */
    public function __construct(CronJobUpdateDataInterface $cronJobUpdateDataInterface)
    {
        parent::__construct();
        
        $this->cronJob = $cronJobUpdateDataInterface;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (User::all() as $user) {
            $this->cronJob->updateContent($user);
        }
    }
}
