<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use Validator;
use DB;
use App\Jobs\SendEmailJob;
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
            $user = $this->user->get();

            return redirect()->intended(route('dashboard'))->with('user', $user);
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

    public function sendEmail()
    {
        $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        dispatch($emailJob);
        echo 'Email sent to inactive user';
    }

    public function resetPasswordNew()
    {
        $data['email'] = $_GET['email'];
        $data['token'] = $_GET['token'];

        return view('auth.passwords.reset')->with('data', $data);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                     'password' => 'required|confirmed',
                    'token' => 'required', ]);

                //check if payload is valid before moving on
                if ($validator->fails()) {
                    return redirect()->back()->withErrors(['email' => 'Please complete the form']);
                }

        $password = $request->password;
        $tokenData = DB::table('users')
                ->where('remember_token', $request->token)->first();

        if (!$tokenData) {
            return view('auth.passwords.email');
        }

        $user = User::where('email', $tokenData->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }
        $user->password = \Hash::make($password);
        $user->remember_token = null;
        $user->status = 1;
        $user->update();
        Auth::login($user);

        if (!empty($user)) {
            $user = $this->user->get();
            return redirect()->intended(route('dashboard'))->with('user', $user);
        } else {
            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        }
    }

    public function send_email($user)
    {
        $user = $this->user->get();
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
