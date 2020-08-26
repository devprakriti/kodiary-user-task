<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Repositories\UserRepository;

class HomeController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     */
    public function __construct(UserRepository $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    }

    public function home()
    {
        $user = User::paginate(50);
        return view('home')->with('user', $user);
    }
}
