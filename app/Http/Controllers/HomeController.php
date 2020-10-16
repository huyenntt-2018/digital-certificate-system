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

    public function download(Certificate $certificate)
    {
        $with = ['user'];
        $data = [
            'id' => $certificate->id,
            'user_id' => Auth::id(),
            'deleted_at' => Null,
        ];
        $certificate = $this->cert->getData($with, $data)->first();

        if (\Route::current()->getName() == 'download-cert') {
            $headers = [
                'Content-Type : application/pem',
            ];
            if ($certificate->type == 0) {
                openssl_x509_export_to_file($certificate->pkcs12['cert'], public_path('/p12/cert'.$certificate->id.'.pem'));
                $file = public_path().'/p12/cert'.$certificate->id.'.pem';
                return Response::download($file, 'cert.pem', $headers);
            } else {
                openssl_x509_export_to_file($certificate->certificate, public_path('/p12/cert_temp_'.$certificate->id.'.pem'));
                $file = public_path().'/p12/cert_temp_'.$certificate->id.'.pem';
                return Response::download($file, 'cert_temp.pem', $headers);
            }
        } else {
            $file = public_path().'/p12/pkcs12_'.$certificate->id.'.p12';
            $headers = [
                'Content-Type : application/p12',
            ];

            return Response::download($file, 'pkcs12.p12', $headers);
        }
    }

    public function checkCert(Request $request)
    {
        $file = \File::get($request->file('file'));
        $certificates = $this->cert->getData();
        foreach ($certificates as $key => $certificate) {
            if ($file == $certificate->certificate) {
                $check = 1;
                break;
            }
            $check = 0;
        }
        if ($check == 1) {
            return back()->withSucc('Chứng thư hợp lệ');
        } else {
            return back()->withErr('Chứng thư không hợp lệ');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
    }
}
