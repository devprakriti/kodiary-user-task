<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use DB;
use App\Repositories\UserRepository;

class LoginController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        } else {
            return view('auth.login');
        }
    }

    public function authenticate(Request $request)
    {
        $data = $request->all('email', 'password');

        if (auth()->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
            dd($this->user->get());

            return redirect()->intended(route('dashboard'));
        } elseif (auth()->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 0])) {
            return redirect(route('reset_password_without_token', $data['email']));
        } elseif (empty($data['email']) || empty($data['password'])) {
            dd('empty');

            return redirect(route('login'))->with('key', 'Username or Password Empty');
        } else {
            dd('invalid');

            return redirect(route('login'))->with('key', 'Invalid Access');
        }
    }
    public function validatePasswordRequest(Request $request, $email)
    {
        $user_detail = DB::table(DB::raw('users force index(users_status_index)'))->where('status', 0)->get();

                // User::where('status', 0)->get();
                $link = [];
        foreach ($user_detail as $key => $u) {

                    // dd($u->email);
                   $new_user = DB::table((DB::raw('users force index(users_status_index)'))->where('email', '=', $u->email)->first())->update(['remember_token ' => Str::random(60)]);

            dd($new_user);
                   // ->update(['remember_token ' => Str::random(60)]);

                    // $user =  DB::table(DB::raw('users force index(users_email_index)'))->where('status', $u->email)->first();    
                    // $user->remember_token = Str::random(60);
                    // $user->created_at = Carbon::now();
                    // $user->update();               
                    // $tokenData =DB::table(DB::raw('users force index(users_email_index)'))->where('status', $u->email)->first();     
                    $link[] = route('passwordresetlink', array($tokenData->remember_token, $u->email));
        }
        dd($link);
    }

        // public function resetPassword(Request $request)
        //     {
        //         //Validate input
        //         $validator = Validator::make($request->all(), [
        //             'email' => 'required|email|exists:users,email',
        //             'password' => 'required|confirmed',
        //             'token' => 'required' ]);

        //         //check if payload is valid before moving on
        //         if ($validator->fails()) {
        //             return redirect()->back()->withErrors(['email' => 'Please complete the form']);
        //         }

        //         $password = $request->password;
        //     // Validate the token
        //         $tokenData = DB::table('password_resets')
        //         ->where('token', $request->token)->first();
        //     // Redirect the user back to the password reset request form if the token is invalid
        //         if (!$tokenData) return view('auth.passwords.email');

        //         $user = User::where('email', $tokenData->email)->first();
        //     // Redirect the user back if the email is invalid
        //         if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);
        //     //Hash and update the new password
        //         $user->password = \Hash::make($password);
        //         $user->update(); //or $user->save();

        //         //login the user immediately they change password successfully
        //         Auth::login($user);

        //         //Delete the token
        //         DB::table('password_resets')->where('email', $user->email)
        //         ->delete();

        //         //Send Email Reset Success Email
        //         if ($this->sendSuccessEmail($tokenData->email)) {
        //             return view('index');
        //         } else {
        //             return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        //         }

        //     }

    public function send_email($user)
    {
        $user = $this->user->get();

        Mail::queue('emails.test', ['user' => 'prakriti'], function ($message) use ($user) {
            $message->to('developer.prakriti@gmail.com', 'prakriti')->subject('Sending Email Using Queue in Laravel 5');
        });
    }

    public function changePassword()
    {
        return view('user::login.change-password');
    }

    public function updatePassword(Request $request)
    {
        $oldPassword = $request->get('old_password');
        $newPassword = $request->get('password');

        $id = Auth::user()->id;
        $users = Auth::user()->find($id);

        if (!(Hash::check($oldPassword, $users->password))) {
            Flash('Old Password Do Not Match !')->error();

            return redirect(route('change-password'));
        } else {
            $data['password'] = Hash::make($newPassword);

            $this->user->update($id, $data);

            Flash('Password Successfully Updated. Please Login Again!')->success();
        }

        Auth::logout();

        return redirect(route('login'));
    }

    public function permissionDenied()
    {
        return view('user::authPermission.permission-denied');
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('login'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
    }
}
