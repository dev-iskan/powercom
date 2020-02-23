<?php

namespace App\Http\Controllers\ApiAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Jobs\CreateAndSaveEskizToken;
use App\Jobs\SendSms;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function getAndSaveToken()
    {
        CreateAndSaveEskizToken::dispatch();
        return response()->json(['message' => 'Successfully created!']);
    }

    public function sendSms(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:12',
            'text' => 'required|string'
        ]);
        SendSms::dispatch($request->phone, $request->text);
        return response()->json(['message' => 'Successfully sent!']);
    }
}
