<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\NewMail;
use Mail;
use DB;
use Illuminate\Support\Str;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        
       

    }

    /**
     * Execute the job.
     */
    public function handle() {   
        $user_detail = DB::table(DB::raw('users force index(users_status_index)'))->where('status', 0)->get(); 
        $link = [];

        foreach ($user_detail as $key => $u) {
           $random_no = Str::random(60);
           $is_updated = DB::table((DB::raw('users force index(users_email_index)')))->where('email', '=', $u->email)->where('status', 0)->update(['remember_token' => $random_no]);
           // $user = DB::table(DB::raw('users force index(users_email_index)'))->where('status', 0)->first();
           $email = $u->email;
           $token = $random_no;          
           $reset_link = url('reset_password_with_token' . '?token=' . $token . '&email=' . $email);
           Mail::to('developer.prakriti@gmail.com')->send(new NewMail($reset_link));
         }                  
    }
}
