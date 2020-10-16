<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NumberRequest;
use App\Http\Requests\RevokeRequest;
use App\Models\Certificate;
use App\User;
use App\Notifications\SendRegisterCert;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\Repositories\NumberRequest\NumberRequestRepositoryInterface;

class RevokeController extends Controller
{
    protected $cert, $requestRevoke, $user;

    public function __construct(NumberRequestRepositoryInterface $requestRevoke, CertificateRepositoryInterface $cert, UserRepositoryInterface $user)
    {
        $this->requestRevoke = $requestRevoke;
        $this->cert = $cert;
        $this->user = $user;
    }

    public function revoke(RevokeRequest $request, User $user)
    {
        $certificate = $this->cert->getCert($request->user_id);

        $data_check_request = [
            'user_id' => $user->id,
            'status' => 3,
        ];
        $checkout_request = $this->requestRevoke->getData(['user'], $data_check_request)->first();

        if (isset($certificate) && !isset($checkout_request)) {
            $data = [
                'user_id' => $user->id,
                'request_of_user' => ['reason' => $request->reason, 'message' => 'Yêu cầu thu hồi chứng thư', 'status' => 3],
                'status' => 3,
            ];
            $request_cert = $this->requestRevoke->create($data);

            $receivers = $this->user->getAllAdmin();
            if (isset($receivers)) {
                foreach ($receivers as $receiver) {
                    $receiver->notify(new SendRegisterCert($user, $request_cert['message'], $request_cert->id));
                }

                return response()->json('success', 200);
            } else {
                return response()->json('fail', 400);
            }
        } else {
            if (!isset($certificate)) {
                return response()->json('Bạn chưa có chứng thư hoặc chứng thư đã bị thu hồi trước đó', 400);
            } else {
                return response()->json('Bạn đã gửi yêu cầu rồi', 400);
            }
        }
    }
}
