<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\NumberRequest\NumberRequestRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Notifications\SendRegisterCert;
use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\Models\Certificate;
use App\Models\Role;
use App\User;

class RegisterRequestController extends Controller
{
    protected $requestCert, $user, $cert;

    public function __construct(NumberRequestRepositoryInterface $requestCert, UserRepositoryInterface $user, CertificateRepositoryInterface $cert)
    {
        $this->requestCert = $requestCert;
        $this->user = $user;
        $this->cert = $cert;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        $certificate = $this->cert->getCert($request->user_id);

        $checkout_request = $this->requestCert->getData(['user'],
            ['user'],
            ['user_id' => $request->user_id, 'status' => 0]
        )->first();

        if (!isset($certificate) && !isset($checkout_request)) {
            $request_of_user = $request->except('user_id');
            $request_of_user['message'] = 'Yêu cầu cấp chứng thư';
            $request_of_user['password'] = encrypt($request->password);
            $request_of_user['type'] = 0;
            $data = [
                'user_id' => $request->user_id,
                'request_of_user' => $request_of_user,
                'status' => 0,
            ];
            $request_cert = $this->requestCert->create($data);

            $receivers = $this->user->getAllAdmin();
            $user = User::where('id', $request->user_id)->first();
            if (isset($receivers)) {
                foreach ($receivers as $receiver) {
                    $receiver->notify(new SendRegisterCert($user, $request_of_user['message'], $request_cert->id));
                }
                return response()->json('success', 200);
            } else {
                return response()->json('fail', 400);
            }
        } else {
            if (isset($certificate)) {
                return response()->json('Bạn đã được cấp chứng thư', 400);
            } else {
                return response()->json('Bạn đã gửi yêu cầu rồi', 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = Certificate::find($id);

        if (isset($certificate)) {
            return $certificate;
        } else {
            return response()->json('Not found', 404);
        }
    }
}
