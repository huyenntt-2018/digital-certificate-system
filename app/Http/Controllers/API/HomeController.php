<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Response;
use App\User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return $user;
    }

    public function download($id)
    {
        $certificate = Certificate::findOrFail($id);
        if (isset($certificate)) {
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
        } else {
            return response()->json('not found', 404);
        }
    }

    public function checkCert(User $user, $serialNumber)
    {
        $data = [
            'user_id' => $user->id,
            'serial_number' => $serialNumber
        ];
        $certificate = Certificate::where($data)->first();
        if (isset($certificate)) {
            return response()->json('success', 200);
        } else {
            return response()->json('not found', 404);
        }
    }
}
