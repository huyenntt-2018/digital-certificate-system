<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Auth;

class CertificateController extends Controller
{
    protected $certificate;

    public function __construct(CertificateRepositoryInterface $certificate)
    {
        $this->certificate = $certificate;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = $this->certificate->getData();

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = Certificate::withTrashed()->where('id', $id)->first();

        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Update status of certificate when expired
     */
    public function status()
    {
        $certificates = $this->certificate->getData(['user'], ['status' => 0]);

        foreach ($certificates as $cert) {
            if (strtotime($cert->valid_to_time) < strtotime(Carbon::now())) {
                $this->certificate->update($cert->id, ['status' => 1]);
                $cert->delete();
            }
        }

        return back()->withSuccess('Cập nhật thành công!');
    }
}
