<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Certificate\CertificateRepositoryInterface;

class CertificateController extends Controller
{
    protected $cert;

    public function __construct(CertificateRepositoryInterface $cert)
    {
        $this->cert = $cert;
    }

    public function sign(Request $request)
    {
        $certificate = $this->cert->getCert($request->user_id);

        if ($certificate) {
            $private_key = $certificate['pkcs12']['pkey'];

            openssl_sign($request->data, $signature, $private_key, 'sha256WithRSAEncryption');

            return $signature;
        } else {
            return 'Người ký không có chứng thư';
        }

    }

    /**
     * Cấu trúc hồ sơ bện án có chứa chữ ký cần như sau:
    * [
    *    'data' => 'Day la vi du',
    *    'signatures' => [
    *        '0' => [
    *           'user_id' => 1,
    *           'sign' => 'abc'
    *        ],
    *        '1' => [
    *           'user_id' => 2,
    *           'sign' => 'abcds'
    *       ]
    *   ]
    * ];
    *
    */
    public function verify(Request $request)
    {
        foreach ($request->signatures as $one_sign) {
            $pub_key = $this->cert->getPubKey($one_sign['user_id']);
            if ($pub_key['key'] == false) {
                return 'chữ ký k hợp lệ';
                break;
            }
            $sign = openssl_verify($request->data, $one_sign['sign'], $pub_key['key'], OPENSSL_ALGO_SHA256);
            if ($sign == 0) {
                return 'chữ ký k hợp lệ';
                break;
            }
        }
        return 'chữ ký hợp lệ';
    }
}
