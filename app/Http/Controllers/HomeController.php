<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\Models\Certificate;
use Response;
use File;
use Auth;
use Session;
use Illuminate\Notifications\Notification;

class HomeController extends Controller
{
    protected $user, $cert;

    public function __construct(UserRepositoryInterface $user, CertificateRepositoryInterface $cert)
    {
        $this->user = $user;
        $this->cert = $cert;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = $this->user->findById(Auth::id());

        return view('page.homepage', compact('user'));
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
    }
}
