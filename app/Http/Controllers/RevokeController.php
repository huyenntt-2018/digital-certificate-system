<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NumberRequest;
use App\Http\Requests\RevokeRequest;
use App\Models\Certificate;
use App\User;
use Auth;
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

    public function revoke(RevokeRequest $request)
    {
        $certificate = $this->cert->getCert($request->user_id);

        $data_check_request = [
            'user_id' => $request->user_id,
            'status' => $request->status,
        ];
        $checkout_request = $this->requestRevoke->getData(['user'], $data_check_request)->first();

        if (isset($certificate) && !isset($checkout_request)) {
            $data = [
                'user_id' => $request->user_id,
                'request_of_user' => $request->all(),
                'status' => $request->status,
            ];
            $request_cert = $this->requestRevoke->create($data);
            $receivers = $this->user->getAllAdmin();

            if (isset($receivers)) {
                foreach ($receivers as $receiver) {
                    $receiver->notify(new SendRegisterCert(Auth::user(), $request->message, $request_cert->id));
                }

                return back()->withSuccess('Gửi yêu cầu thành công!');
            } else {
                return back()->withError('Gửi yêu cầu thất bại!');
            }
        } else {
            if (!isset($certificate)) {
                return back()->withWarning('Bạn chưa có chứng thư hoặc chứng thư đã bị thu hồi trước đó');
            } else {
                return back()->withWarning('Bạn đã gửi yêu cầu rồi');
            }
        }
    }

    public function update(Request $request, NumberRequest $numberRequest, Certificate $certificate)
    {
        $receiver = User::where('id', $request->user_id)->first();
        $data = [
            'status' => $request->status,
            'valid_to_time' => \Carbon\Carbon::now(),
        ];
        $this->cert->update($certificate->id, $data);
        $certificate->delete();
        $numberRequest->update(['status' => $request->status]);

        $receiver->notify(new SendRegisterCert(Auth::user(), $request->message, $numberRequest->id));

        return redirect()->route('number-requests.index')->withSuccess('Đã xử lý thành công');
    }
}
