<?php

namespace App\Services\Payme;

use Closure;

class PaymeBasicMiddleware
{
    public function handle($request, Closure $next)
    {
        $configLogin = config('local.payme_billing_service.login');
//        $configKey = config('local.payme_billing_service.key');
         $configKey = config('local.payme_billing_service.test_key');

        $authorization = $request->header('authorization');
        if ($authorization && 0 === stripos($authorization, 'basic ')) {
            $exploded = explode(':', base64_decode(substr($authorization, 6)), 2);
            if (2 == count($exploded)) {
                list($login, $key) = $exploded;
                if ($login === $configLogin && $key === $configKey) {
                    return $next($request);
                }
            }
        }
        return response()->json(PaymeBillingService::getErrorResponse(PaymeBillingService::ERROR_INSUFFICIENT_PRIVILEGE));
    }
}
