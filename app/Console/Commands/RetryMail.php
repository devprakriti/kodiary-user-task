<?php

namespace App\Console\Commands;
use DB;
use Illuminate\Support\Str;
use App\Mail\NewMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Validator;
use Mail;
use App\Jobs\SendEmailJob;

class InactiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send retry to failed users';

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
      Artisan::call('queue:retry');
    }
}

