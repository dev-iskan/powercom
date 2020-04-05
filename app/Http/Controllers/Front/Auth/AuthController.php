<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSms;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable'
        ], [
            'required' => 'Поле обязательно для заполнения.',
        ]);

        $user = User::where('phone', $request->phone)->whereHas('client')->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => [trans('auth.unregistered')],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => [trans('auth.failed')],
            ]);
        }

        Auth::guard()->attempt($request->only(['phone', 'password']), $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->route('main');
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:12|unique:clients|unique:users',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'accept' => 'required'
        ]);

        $user = DB::transaction(function () use ($request) {

            /** @var User $user */
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            $user->client()->create($request->all());

            return $user;
        });

        Auth::guard()->login($user);

        $this->generateCodeSendCodeAndSaveToCache($user, 'verification_');

        return redirect()->route('show_verify')->with('message', 'Код отправлен на ваш телефон номер');
    }

    public function showVerifyForm()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|digits:4'
        ], [
            'required' => 'Поле обязательно для заполнения.',
            'digits' => 'Код должен быть :digits цифер.',
        ]);

        $user = auth()->user();
        $cacheKey = 'verification_' . $user->phone;
        $cachedPhone = Cache::get($cacheKey);

        if (!$cachedPhone || $cachedPhone['tries'] == 3) {
            return back()->with('message', 'Код не отправлен или срок кода истек');
        }

        if (!in_array($request->code, $cachedPhone['codes'])) {
            $cachedPhone['tries']++;
            Cache::put($cacheKey, $cachedPhone, 300);
            return back()->with('message', 'Неверный код');
        }

        $user->phone_verified_at = now();
        $user->save();

        Cache::forget($cacheKey);
        return redirect()->route('main');
    }

    public function sendCode()
    {
        $this->generateCodeSendCodeAndSaveToCache(auth()->user(), 'verification_');
        return back()->with('message', 'Код успешно отправлен');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('main');
    }

    public function showRequestPasswordReset()
    {
        return view('auth.request_password_reset');
    }

    public function requestPasswordReset(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:12'
        ], [
            'required' => 'Поле обязательно для заполнения.',
            'digits' => 'Код должен быть :digits цифры.',
        ]);
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('message', 'Пользователь с данным номером не найден');
        }

        $this->generateCodeSendCodeAndSaveToCache($user, 'password_reset_');

        session()->put('password_reset', $user->phone);
        return redirect()->route('password_reset')->with('message', 'Код успешно отправлен');
    }

    public function showPasswordReset()
    {
        return view('auth.password_reset');
    }

    public function passwordReset(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:12',
            'password' => 'required|min:8',
            'code' => 'required'
        ], [
            'required' => 'Поле обязательно для заполнения.',
            'digits' => 'Код должен быть :digits цифер.',
            'min' => 'Минимальное количество :digits цифер.',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->with('message', 'Пользователь с данным номером не найден');
        }

        $cacheKey = 'password_reset_' . $user->phone;
        $cachedPhone = Cache::get($cacheKey);

        if (!$cachedPhone || $cachedPhone['tries'] == 3) {
            return back()->with('message', 'Код не отправлен или срок кода истек');
        }

        if (!in_array($request->code, $cachedPhone['codes'])) {
            $cachedPhone['tries']++;
            Cache::put($cacheKey, $cachedPhone, 300);
            return back()->with('message', 'Неверный код');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('password_reset');
        Cache::forget($cacheKey);
        return redirect()->route('main')->with('message', 'Пароль успешно обновлен');
    }

    protected function generateCodeSendCodeAndSaveToCache($user, $key)
    {
        if (config('app.env') == 'production') {
            $code = rand(1000, 9999);
            SendSms::dispatch($user->phone, 'Powercom.uz. Vash kod podverjdenia: ' . $code);
        } else {
            $code = 1111;
        }

        $cacheKey = $key . $user->phone;
        $cachedPhone = Cache::get($cacheKey);
        if (empty($cachedPhone['codes'])) {
            Cache::put($cacheKey, ['codes' => [$code], 'tries' => 0], 600);
        } else {
            $codes = $cachedPhone['codes'];
            $tries = $cachedPhone['tries'];

            if (count($codes) == 3) {
                return back()->withErrors(['message' => 'Лимит кодов исчерпан. Подождите 10 минут']);
            }

            $mergedCodes = array_merge([$code], $codes);
            Cache::put($cacheKey, ['codes' => $mergedCodes, 'tries' => $tries], 600);
        }
    }

}
