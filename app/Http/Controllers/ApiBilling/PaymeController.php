<?php

namespace App\Http\Controllers\ApiBilling;

use App\Http\Controllers\Controller;
use App\Services\Payme\PaymeBasicMiddleware;
use App\Services\Payme\PaymeBillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymeController extends Controller
{
    public function __construct()
    {
        $this->middleware(PaymeBasicMiddleware::class);
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'method' => 'required|string',
            'params' => 'required|array'
        ]);
        if ($validator->fails()) {
            response()->json(PaymeBillingService::getErrorResponse(PaymeBillingService::ERROR_INVALID_JSON_RPC_OBJECT));
        }

        switch ($request->input('method')) {
            case 'CheckPerformTransaction':
                return response()->json(PaymeBillingService::CheckPerformTransaction($request));
                break;
            case 'CheckTransaction':
                return response()->json(PaymeBillingService::CheckTransaction($request));
                break;
            case 'CreateTransaction':
                return response()->json(PaymeBillingService::CreateTransaction($request));
                break;
            case 'PerformTransaction':
                return response()->json(PaymeBillingService::PerformTransaction($request));
                break;
            case 'CancelTransaction':
                return response()->json(PaymeBillingService::CancelTransaction($request));
                break;
            case 'ChangePassword':
                return response()->json(PaymeBillingService::ChangePassword());
                break;
            case 'GetStatement':
                return response()->json(PaymeBillingService::GetStatement($request));
                break;
            default:
                return response()->json(PaymeBillingService::getErrorResponse(PaymeBillingService::ERROR_METHOD_NOT_FOUND));
        }
    }
}
