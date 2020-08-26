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
    protected $signature = 'inactive:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to inactive users';

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
        $user_detail = DB::table(DB::raw('users force index(users_status_index)'))->where('status', 0)->get();
        
        $link = [];
        
        foreach ($user_detail as $key => $u) {
            $random_no = Str::random(60);
            $is_updated = DB::table((DB::raw('users force index(users_email_index)')))->where('email', '=', $u->email)->where('status', 0)->update(['remember_token' => $random_no]);
            $email = $u->email;
            $token = $random_no;
            $user['detail'] = User::where('email', $u->email)->first();
            $user['reset_link'] = url('reset_password_with_token'.'?token='.$token.'&email='.$email);
            Mail::to($u->email)->send(new NewMail($user));
        }
        $this->info('Inactive User');
    }
}

