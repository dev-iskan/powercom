<?php

namespace App\Http\Controllers\ApiBilling;

use App\Http\Controllers\Controller;
use App\Services\Click\ClickBillingService;
use Illuminate\Http\Request;

class ClickController extends Controller
{
    public function prepare(Request $request)
    {
        return ClickBillingService::prepare($request);
    }

    public function complete(Request $request)
    {
        return ClickBillingService::complete($request);
    }
}
